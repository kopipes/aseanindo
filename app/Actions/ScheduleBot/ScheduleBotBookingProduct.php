<?php
namespace App\Actions\ScheduleBot;

use App\Helpers\Yellow;
use App\Models\Data\User;
use App\Models\Data\Ticket;
use Illuminate\Support\Str;
use App\Models\Data\Contact;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Models\Data\TicketForm;
use App\Models\Util\SlaSetting;
use App\Models\Data\TicketAgent;
use App\Models\Data\TicketHistory;
use Illuminate\Support\Facades\DB;
use App\Models\Data\CompanyProduct;
use App\Models\Data\CustomerDetail;
use App\Helpers\BadRequestException;
use Illuminate\Support\Facades\Cache;
use App\Services\Company\CompanyService;
use App\Models\Data\TicketScheduleDetail;
use App\Models\Data\ProductScheduleDetail;
use App\Models\Data\CompanyContactCustomer;
use App\Jobs\CallnChat\SendRatingBookingTicket;

class ScheduleBotBookingProduct
{
     public function handle(Request $request, $companyId)
     {
          $productId = $request->product_id;
          $session = $request->session;

          $product = CompanyProduct::query()
               ->with(['detail:product_id,location,start_date,end_date,start_time,end_time,max_booking'])
               ->where('id', $productId)
               ->where('company_id', $companyId)
               ->whereNotNull('chatbot_forms')
               ->select(['id', 'name', 'image', 'description', 'category', 'chatbot_forms', 'closing_statement', 'verification_email', 'form_template_id'])
               ->firstOrFail();

          $alreadyVerifiyEmail = Cache::get("verify-booking-bot-product-" . $productId . $companyId . $session);
          if ($product->verification_email && !$alreadyVerifiyEmail) {
               throw new BadRequestException('Please confirm your email first to continue booking');
          }

          // create ticket
          try {
               /**
                * Create ticket, send email invoice
                */
               DB::transaction(function () use ($request, $companyId, $product) {
                    $productCategory = $request->product_category;
                    $productId = $request->product_id;
                    $customerEmail = $request->email;
                    $customerName = $request->name;
                    $bookingQuantity = $request->quantity ?: 1;
                    $subjectId = $request->subject_id;
                    $chatBotForms = $request->forms;
                    $time = @$chatBotForms['time'];
                    $counselingDate = @$chatBotForms['date'];

                    $customerId = $this->createOrUpdateCustomerContact($request, $companyId);

                    $categoryProduct = "general";
                    $productCategoryValue = "General"; // 'General','Schedule','Schedule Professional','Schedule Other','Other','Campaign','Form'
                    switch ($productCategory) {
                         case 'schedule_professional':
                              $productCategoryValue = 'Schedule Professional';
                              $categoryProduct = 'schedule';
                              break;
                         case 'schedule_other':
                              $productCategoryValue = 'Schedule Other';
                              $categoryProduct = 'schedule';
                              break;
                    }


                    if ($categoryProduct === 'schedule') {
                         $productSchedule = ProductScheduleDetail::where('product_id', $productId)->firstOrFail();
                         $counselingTime = json_decode($productSchedule->counseling_time);
                         $counselingTime = (array) collect($counselingTime->{$counselingDate})->where('id', $time['id'])->first();
                         $maxBooking = intval($counselingTime['max'] ?: 0);
                         $totalBooked = TicketScheduleDetail::query()
                              ->where('product_id', $productId)
                              ->where('counseling_date', $counselingDate)
                              ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(JSON_UNQUOTE(counseling_time), '$.start'))=?", [@$counselingTime['start']])
                              ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(JSON_UNQUOTE(counseling_time), '$.end'))=?", [@$counselingTime['end']]);
                         if ($productCategory === 'schedule_professional') {
                              $maxBooked = $maxBooking + 1;
                              $totalBooked = $totalBooked->count();
                         } elseif ($productCategory === 'schedule_other') {
                              $totalBooked = $totalBooked->sum('number');
                              $maxBooked = $maxBooking + intval($bookingQuantity);
                         }
                         if ($totalBooked > $maxBooked) {
                              throw new BadRequestException('The schedule is full');
                         }
                    }
                    $subject = DB::table('company_product_subjects')
                         ->where('id', $subjectId)
                         ->first();
                    $priority = $subject?->priority ?: "Low";
                    $sla = $this->findSlaSetting($companyId, $priority);
                    $slaDivisionTime = $this->findSlaDivisionSetting($companyId, $subject?->escalation_team_id, $priority);

                    list($resolutionHours, $resolutionMinutes) = explode(':', $sla->resolution);
                    list($responseHours, $responseMinutes) = explode(':', $sla->response);
                    list($waitingHours, $waitingMinutes) = explode(':', $sla->waiting_time);

                    list($slaDivisionHour, $slaDivisionMinutes) = explode(':', $slaDivisionTime);

                    $date = now();
                    $escalationTeamSubject = $subject?->escalation_team_id;
                    $ticketUsers = collect([]);
                    $currentAgentId = null;
                    $spvId = null;
                    $userCategory = "inbound";
                    if ($subject?->escalation_team_id) {
                         $ticketUsers = DB::table('company_product_subject_users')
                              ->where('subject_id', $subjectId)
                              ->where('company_id', $companyId)
                              ->pluck('user_id');
                         $userCategory = "escalation";
                    }
                    if (!count($ticketUsers)) {
                         $ticketUsers = DB::table('product_helpdesk')
                              ->join('user_helpdesk', 'user_helpdesk.helpdesk_id', 'product_helpdesk.helpdesk_id')
                              ->where('product_helpdesk.product_id', $productId)
                              ->where('user_helpdesk.role', 'agent')
                              ->pluck('user_helpdesk.user_id');
                         $escalationTeamSubject = null;
                    }


                    if (count($ticketUsers) == 1) {
                         $currentAgentId = $ticketUsers[0];
                         $spvId = (new CompanyService)->findSpvAgent($currentAgentId, $companyId, $userCategory);
                    }


                    $responseTimeOfficeHour = $this->getDateWithOfficeHour($companyId, $date, $responseHours, $responseMinutes, $waitingHours, $waitingMinutes);
                    $resolutionTimeOfficeHour = $this->getDateWithOfficeHour($companyId, $date, $resolutionHours, $resolutionMinutes, $waitingHours, $waitingMinutes);
                    $devisionTimeOfficeHour = $this->getDateWithOfficeHour($companyId, $date, $slaDivisionHour, $slaDivisionMinutes, $waitingHours, $waitingMinutes);


                    $ticket = Ticket::create([
                         'source' => 'From Web Bot',
                         'status' => 'From Bot',
                         'type' => 'inbound',
                         'category' => 'Incoming Call',
                         'number_id' => '',
                         'note' => '',
                         'remark' => '',
                         'ticket_number' => Yellow::createTicketNumber($categoryProduct),
                         'company_id' => $companyId,
                         'product_id' => $productId,
                         'product_name' => $product->name,
                         'product_category' => $productCategoryValue,
                         'customer_name' => $customerName,
                         'customer_id' => $customerId,
                         'form_template_id' => $product->form_template_id,
                         'priority' => $priority,
                         "escalation_team_id" => $escalationTeamSubject,
                         "subject_id" => $subjectId,
                         'current_agent_id' => $currentAgentId,
                         'spv_id' => $spvId,
                         'is_new' => true,
                         'sla_resolution_time' => [
                              'start_at' => $resolutionTimeOfficeHour->start_at,
                              'end_at' => $resolutionTimeOfficeHour->end_at,
                              'duration' => $sla->resolution,
                              'solved_at' => '',
                              'solved_duration' => '',
                         ],
                         'sla_response_time' => [
                              'start_at' => $responseTimeOfficeHour->start_at,
                              'end_at' => $responseTimeOfficeHour->end_at,
                              'duration' => $sla->response,
                              'solved_at' => '',
                              'solved_duration' => '',
                              'auto_closed_at' => $responseTimeOfficeHour->auto_closed_at,

                         ],
                         'sla_division' => $escalationTeamSubject ? [
                              'start_at' => $devisionTimeOfficeHour->start_at,
                              'end_at' => $devisionTimeOfficeHour->end_at,
                              'duration' => $slaDivisionTime,
                              'solved_at' => '',
                              'solved_duration' => ''
                         ] : null,

                    ]);
                    // insert ticket agent by subject user

                    TicketAgent::insert(
                         $ticketUsers->map(fn($agentId, $index) => [
                              'id' => Str::uuid(),
                              'created_at' => now()->addSeconds($index),
                              'company_id' => $companyId,
                              'ticket_id' => $ticket->id,
                              'agent_id' => $agentId
                         ])->toArray()
                    );

                    Ticket::query()
                         ->where('customer_id', $customerId)
                         ->where('company_id', $companyId)
                         ->update([
                              'customer_name' => $customerName
                         ]);

                    $historyDetail = null;
                    $queueNumber = null;
                    if ($categoryProduct === 'schedule') {
                         // BOOKING SCHEDULE
                         $productScheduleDetail = ProductScheduleDetail::where("product_id", $productId)->first();
                         if ($productScheduleDetail) {
                              $totalBooked = TicketScheduleDetail::query()
                                   ->where('product_id', $productId)
                                   ->where('counseling_date', $counselingDate)
                                   ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(JSON_UNQUOTE(counseling_time), '$.start'))=?", [@$counselingTime['start']])
                                   ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(JSON_UNQUOTE(counseling_time), '$.end'))=?", [@$counselingTime['end']])
                                   ->sum('number');
                              $bookingNumber = $totalBooked + 1;
                              $queueNumber = $bookingNumber;

                              $quantityNumber = $request->quantity ?: 1;
                              TicketScheduleDetail::create([
                                   'company_id' => $companyId,
                                   'ticket_id' => $ticket->id,
                                   'product_id' => $productId,
                                   'product_schedule_detail_id' => $productScheduleDetail->id,
                                   'schedule_category' => $productCategoryValue,
                                   'name' => $customerName,
                                   'number' => $quantityNumber,
                                   'queue_number' => $bookingNumber,
                                   'counseling_date' => $counselingDate,
                                   'counseling_time' => $counselingTime
                              ]);

                              $historyDetail = json_encode([
                                   'name' => $customerName,     
                                   'date' => date('d M, Y'),     
                                   'time' => date('H:i'),     
                                   'number' => 1, 
                                   'queue_number' => $bookingNumber,
                                   'counseling_date' => $counselingDate,    
                                   'counseling_time_start' => @$counselingTime['start'],    
                                   'counseling_time_end' => @$counselingTime['end'],    
                              ]);
                              
                         }
                    }

                    $division = DB::table('escalation_teams')
                         ->where('id', $escalationTeamSubject)
                         ->select(['name'])
                         ->first();
                    // Create Ticket History
                    $ticketHistory = TicketHistory::create([
                         'company_id' => $companyId,
                         'ticket_id' => $ticket->id,
                         'note' => '',
                         'remark' => '',
                         'status' => 'From Bot',
                         'division_id' => $escalationTeamSubject,
                         'division_name' => $division?->name,
                         'sla_priority' => $priority,
                         'sla_resolution_time' => $sla->resolution,
                         'sla_response_time' => $sla->response,
                         'division_sla' => $escalationTeamSubject ? $slaDivisionTime : '00:00',
                         'helpdesk_id' => $ticket->helpdesk_id,
                         'helpdesk_name' => $ticket->helpdesk_name,
                         'details' => $historyDetail
                    ]);

                    $formField = DB::table('inbound_form_template_fields')
                         ->where('inbound_form_template_id', $product?->form_template_id)
                         ->orderBy('sorting', 'asc')
                         ->get()
                         ->map(function ($row, $index) use ($companyId, $ticket, $ticketHistory, $chatBotForms) {
                              $content = @$chatBotForms[$row->slug] ?: '';
                              if($row->type=='file'){
                                   $content = @$chatBotForms[$row->slug] ? asset(Yellow::uploadFile(@$chatBotForms[$row->slug], 'chatbot')) : '';
                              }
                              return [
                                   'id' => Str::uuid(),
                                   'created_at' => now()->addSeconds($index),
                                   'company_id' => $companyId,
                                   'ticket_id' => $ticket->id,
                                   'ticket_history_id' => $ticketHistory?->id,
                                   'input_type' => $row->type,
                                   'label' => $row->label,
                                   'slug' => $row->slug,
                                   'sorting' => $row->sorting,
                                   'content' => $content,
                                   'template_field_id' => $row->id,
                                   'group_name' => $row->group_name,
                                   'group_sorting' => $row->group_name,
                                   'is_default_form_escalation' => $row->is_default_form_escalation,
                                   'add_to_escalation_team' => $row->add_to_escalation_team,
                                   'options' => $row->options ? json_encode($row->options) : null,
                                   'form_category' => $row->form_category,
                                   'share_form' => $row->is_shared,
                                   'escalation_1_shared_id' => $row->escalation_1_shared_id,
                                   'escalation_2_shared_id' => $row->escalation_2_shared_id,
                                   'division_id' => $row->division_id,
                              ];
                         })
                         ->toArray();
                    TicketForm::insert($formField);


                    $company = Company::find($companyId);
                    SendRatingBookingTicket::dispatch($company, $customerEmail, $customerName, $ticket,$queueNumber);
               });

          } catch (BadRequestException $e) {
               throw new BadRequestException($e->getMessage());
          } catch (\Exception $e) {
               logger($e);
               throw new BadRequestException('Terdapat kesalahan server !');
          }
     }


     private function createOrUpdateCustomerContact(Request $request, $companyId)
     {
          $customerEmail = $request->email;
          $customerPhone = Yellow::phoneNumber($request->phone_number, $request->phone_number_code);
          $customerName = $request->name;
          $user = User::query()
               ->where('role', 'customer')
               ->where('email', $customerEmail)
               ->first();

          if (!$user) {
               $user = User::create([
                    "platform" => "web",
                    'role' => 'customer',
                    'name' => $customerName,
                    'phone' => $customerPhone,
                    'phone_code' => $request->phone_number_code,
                    'email' => $customerEmail,
                    'password' => '',
                    'username' => Str::slug(strtolower($customerName), '_') . rand(111, 999),
                    'profile' => config('services.placeholder_avatar'),
               ]);

               CustomerDetail::create([
                    'user_id' => $user->id,
                    'personal_name' => $customerName,
                    'personal_email' => $customerEmail,
                    'phone_additional' => $customerPhone,
                    'phone_additional_code' => $request->phone_number_code,
               ]);

               Contact::create([
                    'customer_id' => $user->id,
                    'company_id' => $companyId,
                    'role' => null
               ]);

               CompanyContactCustomer::create([
                    'customer_id' => $user->id,
                    'company_id' => $companyId,
                    'name' => $customerName,
                    'email' => $customerEmail,
                    'phone' => '',
                    'phone_code' => '',
                    'additional_phone' => $customerPhone,
                    'additional_phone_code' => $request->phone_number_code,
               ]);

               return $user->id;

          } else {
               $userContactData = [
                    'customer_id' => $user->id,
                    'company_id' => $companyId
               ];
               Contact::updateOrCreate($userContactData, [
                    ...$userContactData,
                    'role' => null,

               ]);
               CompanyContactCustomer::updateOrCreate($userContactData, [
                    ...$userContactData,
                    'name' => $customerName,
                    'email' => $customerEmail,
                    'additional_phone' => $customerPhone,
                    'additional_phone_code' => $request->phone_number_code,
               ]);
               return $user->id;
          }
     }

     private function findSlaSetting($companyId, $priority)
     {
          $data = [];
          $sla = SlaSetting::where("company_id", $companyId)->where("category", "inbound")->first();
          if (!$sla)
               return (object) ['response' => '00:00', 'resolution' => '00:00', 'waiting_time' => '00:00'];
          switch ($priority) {
               case 'Critical':
                    $data['response'] = $sla->critical['response'];
                    $data['resolution'] = $sla->critical['resolution'];
                    $data['waiting_time'] = $sla->waiting_time;
                    break;
               case 'High':
                    $data['response'] = $sla->hight['response'];
                    $data['resolution'] = $sla->hight['resolution'];
                    $data['waiting_time'] = $sla->waiting_time;
                    break;
               case 'Medium':
                    $data['response'] = $sla->medium['response'];
                    $data['resolution'] = $sla->medium['resolution'];
                    $data['waiting_time'] = $sla->waiting_time;
                    break;
               case 'Low':
                    $data['response'] = $sla->low['response'];
                    $data['resolution'] = $sla->low['resolution'];
                    $data['waiting_time'] = $sla->waiting_time;
                    break;
          }

          return (object) $data;
     }

     private function findSlaDivisionSetting($companyId, $divisonId, $priority)
     {
          $sla = DB::table('company_sla_devisions')
               ->where("company_id", $companyId)
               ->where('escalation_team_id', $divisonId)
               ->where("category", "inbound")
               ->first();
          if (!$sla) {
               return '00:00';
          }
          $priority = strtolower($priority);
          if ($priority == 'high') {
               $priority = 'hight';
          }
          $key = "{$priority}_division_sla";
          return @$sla->{$key} ?: '00:00';
     }

     private function findOfficeHour($companyId)
     {
          $officeHour = SlaSetting::where("category", 'inbound')->where("company_id", $companyId)->first();
          if ($officeHour && $officeHour->office_hour_type == 'custom') {
               $office_hour = json_decode(json_encode($officeHour->office_hour));
               if (
                    !$office_hour->friday_on &&
                    !$office_hour->monday_on &&
                    !$office_hour->sunday_on &&
                    !$office_hour->tuesday_on &&
                    !$office_hour->saturday_on &&
                    !$office_hour->thursday_on &&
                    !$office_hour->wednesday_on
               ) {
                    return null;
               }

               $officeHour->office_hour = $office_hour;
          }

          return $officeHour;
     }
     private function getDateWithOfficeHour($companyId, $paramDate, $responseHours, $responseMinutes, $waitingHours = null, $waitingMinutes = null)
     {
          $officeHour = $this->findOfficeHour($companyId);

          $date = $paramDate->copy();

          // Calculate initial endAt and autoClosedAt times
          $startAt = $date->copy();
          $endAt = $date->copy()->addHours($responseHours)->addMinutes($responseMinutes);
          $autoClosedAt = $waitingHours ? $date->copy()->addHours($waitingHours)->addMinutes($waitingMinutes) : $endAt;

          // If there are office hours, adjust the times
          if ($officeHour && $officeHour->office_hour_type == 'custom') {
               $office_hour = json_decode(json_encode($officeHour->office_hour));
               $currentDay = strtolower($date->format('l')); // Get current day
               $currentTime = $date->format('H:i:s'); // Get current time
               $currentDayOn = $currentDay . "_on";
               $officeTime = $office_hour->$currentDay;
               $officeTimeOn = $office_hour->$currentDayOn;

               if ($officeTimeOn && $currentTime < $officeTime->start . ":00" && $currentTime <= $officeTime->end . ":00") {
                    $startAt = $date->copy()->setTimeFromTimeString($officeTime->start);
                    $endAt = $date->copy()->setTimeFromTimeString($officeTime->start)->addHours($responseHours)->addMinutes($responseMinutes);
               }

               // If current time exceeds the end of office hours for the day, adjust endAt and autoClosedAt
               while (!$officeTimeOn || ($officeTimeOn && $currentTime > $officeTime->end . ":00")) {
                    // Increment the date by one day to find the next available office day
                    $date = $date->addDay();

                    // Update current time to the start of the day
                    $currentTime = '00:00:00';
                    // Update current day
                    $currentDay = strtolower($date->format('l'));
                    $currentDayOn = $currentDay . "_on";
                    // Update office hours for the new day
                    $officeTime = $office_hour->$currentDay;
                    $officeTimeOn = $office_hour->$currentDayOn;

                    if ($officeTimeOn) {
                         $startAt = $date->copy()
                              ->setTimeFromTimeString($officeTime->start);
                         $endAt = $date->copy()
                              ->setTimeFromTimeString($officeTime->start)
                              ->addHours($responseHours)
                              ->addMinutes($responseMinutes);
                         $autoClosedAt = $paramDate->copy()
                              ->addHours($waitingHours)
                              ->addMinutes($waitingMinutes);
                         // ->setTimeFromTimeString($officeTime->start)
                    }

               }

               // Set endAt and autoClosedAt based on next available office hours
          }

          if (!$officeHour) {
               return (object) [
                    'start_at' => '',
                    'end_at' => '',
                    'auto_closed_at' => '',
                    'has_office_hour' => true
               ];
          }

          // Return calculated startAt, endAt, and autoClosedAt times
          return (object) [
               'start_at' => $startAt->format('Y-m-d H:i:s'),
               'end_at' => $endAt->format('Y-m-d H:i:s'),
               'auto_closed_at' => $autoClosedAt->format('Y-m-d H:i:s'),
               'has_office_hour' => true
          ];
     }
}