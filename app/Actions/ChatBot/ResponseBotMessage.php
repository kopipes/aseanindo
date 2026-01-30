<?php
namespace App\Actions\ChatBot;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\BadRequestException;
use App\Helpers\Yellow;
use App\Models\Data\ProductScheduleDetail;
use App\Models\Data\TicketScheduleDetail;
use App\Services\CallnChat\ChatBotService;
use Illuminate\Validation\ValidationException;

class ResponseBotMessage
{
     public function __construct(
          private $chatBotService = new ChatBotService
     ) {
     }
     public function handle(Request $request, $botId)
     {
          $result = null;
          DB::transaction(function () use (&$result, $request, $botId) {
               $message = $request->message;
               $type = $request->subject_id ? 'form' : $request->type;

               $chatBot = $this->chatBotService->findById($botId);
               if (!$chatBot->form_complete) {
                    if ($type === 'form') {
                         $this->insertFormValue($request, $chatBot, $message);
                    }
                    $result = $this->createFormSceneFlow($request, $chatBot);
               } else {
                    $currentStep = $chatBot->current_step;
                    $rejectionMessage = $chatBot->rejection_message;
                    $current_scene = $chatBot->current_scene;
                    if ($currentStep['type'] == 'message_option') {
                         $nextFlow = null;
                         $this->findSelectedOption($nextFlow, $current_scene, $message);
                         if (!$nextFlow) {
                              if (!$result = $this->findFlowByKeyword($chatBot, $message)) {
                                   $result = $this->rejectionWithRepeatCurrent($rejectionMessage, $current_scene, $botId);
                              }
                         } else {
                              $result = $this->nextFlowFromOption($chatBot, $nextFlow);
                         }
                    } else if ($currentStep['type'] === 'message_data_request' && $type !== 'product') {
                         if (!$result = $this->findFlowByKeyword($chatBot, $message)) {
                              $result = $this->rejectionWithRepeatCurrent($rejectionMessage, $current_scene, $botId);
                         }
                    }
                    if (!$result && !$chatBot->next_scene) {
                         if (!$result = $this->findFlowByKeyword($chatBot, $message)) {
                              $result = $this->rejectionMessage($rejectionMessage, $botId);
                         }
                    }
               }
          });

          return $result;

     }

     private function findFlowByKeyword($chatBot, $message)
     {
          $flow = collect($chatBot->all_flows)->filter(function ($item) use ($message) {
               $message = strtolower($message);
               return collect($item['keywords'])->filter(fn($keyword) => str_contains(strtolower($keyword), $message) || str_contains($message, strtolower($keyword)))->count();
          })->first();
          if (!$flow) {
               return null;
          }

          $nextFlow = $flow['next'];
          $currentFlow = $flow;
          unset($currentFlow['next']);
          $chatBot->update([
               'flow' => $flow,
               'current_scene' => $currentFlow,
               'next_scene' => $nextFlow,
               'current_step' => [
                    'index' => 1,
                    'type' => $currentFlow['type']
               ],
          ]);

          $this->chatBotService->message::create([
               'chatbot_id' => $chatBot->id,
               'sender' => 'bot',
               'message' => json_encode($currentFlow),
               'message_type' => "bot_" . $currentFlow['type'],
          ]);

          $message = $chatBot['current_scene'];

          return [
               'message' => [
                    'message' => $this->chatBotService->handleMessageOption($message),
                    'sender' => 'bot',
                    'message_type' => "bot_" . $message['type'],
                    'time' => date('H:i')
               ],
               'type' => $message['type'],
               'session_id' => $chatBot->id
          ];

     }
     private function insertFormValue($request, &$chatBot, $message)
     {
          $customerData = $chatBot->customer_data;
          $productFormScene = $chatBot->product_form_scene;

          $code = null;

          // UPDATE LAST MESSAGE FORM
          $messageForm = $this->chatBotService->message::query()
               ->where('chatbot_id', $chatBot->id)
               ->where('message_type', 'bot_form')
               ->orderBy('created_at', 'desc')
               ->select(['message', 'id'])
               ->first();

          if (!@$customerData['name']) {
               // UPDATE NAME CONTENT
               $customerData['name'] = $message;
          } else if (!@$customerData['email']) {
               $request->validate([
                    'message' => 'email'
               ]);
               $customerData['email'] = $message;
          } else if (!@$customerData['phone_number']) {
               $phoneNumber = $message['value'];
               if (substr($phoneNumber, 0, 1) == '0') {
                    throw ValidationException::withMessages(['phone_number' => "Please do not put 0 (zero) in front of the phone number"]);
               }
               $customerData['phone_number'] = $phoneNumber;
               $customerData['phone_code'] = $message['code'];
               $code = $message['code'];
               $message = $message['value'];
          } else if (!@$customerData['subject_id'] && $request->subject_id) {
               $customerData['subject_id'] = $request->subject_id;
          } else if (!@$customerData['quantity'] && @$customerData['product_category'] === 'schedule_other') {
               $maxBooking = $messageForm->message->max_booking;
               $already_booked = intval($messageForm->message->already_booked);
               $maxBooking = $maxBooking - $already_booked;
               if ($maxBooking <= 0) {
                    throw ValidationException::withMessages(['quantity' => "The schedule is full"]);
               }
               if (intval($message) > $maxBooking) {
                    throw ValidationException::withMessages(['quantity' => "Quantity cannot exceed $maxBooking"]);
               }
               $customerData['quantity'] = $message;
          } else {
               $form = $productFormScene[0];
               if ($form['type'] === 'file') {
                    $message = asset(Yellow::uploadFile($message, 'chatbot'));
               }
               $customerDataForm = collect($customerData['forms'])->map(function ($row) use ($form, $message) {
                    if ($row['id'] === $form['id']) {
                         $row['content'] = $message;
                    }
                    return $row;
               });
               array_shift($productFormScene);
               $customerData['forms'] = $customerDataForm->toArray();
          }
          if ($messageForm) {
               $messageValue = $messageForm->message;
               $messageValue->value = $message;
               $messageValue->code = $code;
               $messageForm->update([
                    'message' => json_encode($messageValue)
               ]);
          }
          $chatBot->update([
               'customer_data' => $customerData,
               'product_form_scene' => $productFormScene
          ]);
     }
     private function createFormSceneFlow($request, $chatBot)
     {
          $customerData = $chatBot->customer_data;
          $productFormScene = $chatBot->product_form_scene;

          $lastOption = false;
          $message = null;
          $next = null;
          $messageType = "bot_form";
          if (!@$customerData['name']) {
               $message = [
                    'label' => 'Fullname',
                    'group' => null,
                    'type' => 'text',
                    'slug' => 'name',
                    'value' => '',
                    'options' => []
               ];
          } else if (!@$customerData['email']) {
               $message = [
                    'label' => 'Email Address',
                    'group' => null,
                    'type' => 'email',
                    'slug' => 'email',
                    'value' => '',
                    'options' => []
               ];
          } else if (!@$customerData['phone_number']) {
               $message = [
                    'label' => 'Phone Number',
                    'group' => null,
                    'type' => 'phone_number',
                    'slug' => 'phone_number',
                    'code' => '62',
                    'value' => '',
                    'options' => []
               ];
               if ((!$productFormScene || count($productFormScene ?: []) == 1) && @$customerData['product_category'] !== 'schedule_other') {
                    $lastOption = true;
               }
          }  else if (!@$customerData['quantity'] && @$customerData['product_category'] === 'schedule_other') {
               $productScheduleDetail = ProductScheduleDetail::where('product_id', $customerData['product_id'])->select(['max_booking'])->first();
               $alreadyBooked = TicketScheduleDetail::where('product_id', $customerData['product_id'])->sum('number');
               $message = [
                    'label' => 'Quantity',
                    'group' => null,
                    'type' => 'quantity',
                    'slug' => 'quantity',
                    'value' => '',
                    'max_booking' => $productScheduleDetail->max_booking,
                    'already_booked' => $alreadyBooked,
                    'options' => []
               ];
               if (!$productFormScene || count($productFormScene ?: []) == 1) {
                    $lastOption = true;
               }
          } else {
               $form = @$productFormScene[0];
               $lastOption = count($productFormScene ?: []) == 1;
               if ($form) {
                    $message = [
                         'label' => @$form['label'],
                         'group' => @$form['group_name'],
                         'type' => @$form['type'],
                         'slug' => @$form['slug'],
                         'value' => '',
                         'options' => @$form['options']
                    ];
               }  else if (!@$customerData['subject_id']) {
                    $message = [
                         'label' => 'Choose Subject',
                         'group' => null,
                         'type' => 'subject',
                         'slug' => 'subject',
                         'value' => '',
                         'options' => $this->findAllProductSubject($customerData['product_id'])
                    ];
                    if ((!$productFormScene || count($productFormScene ?: []) == 1) && @$customerData['product_category'] !== 'schedule_other') {
                         $lastOption = true;
                    }
               } else {
                    $booking = (new BookingTicketFromBot)->handle($request, $chatBot->id);
                    $message = @$booking['message']['message'];
                    $next = @$booking['next'];
                    $messageType = $booking['type'];
               }
          }

         

          if ($messageType == 'bot_form') {
               $this->chatBotService->message::create([
                    'chatbot_id' => $chatBot->id,
                    'sender' => 'bot',
                    'message' => json_encode($message),
                    'message_type' => 'bot_form',
               ]);
          }

          return [
               'message' => [
                    'message' => $message,
                    'message_type' => $messageType,
                    'sender' => 'bot',
                    'time' => date('H:i'),
                    'lastOption' => $lastOption
               ],
               'next' => $next,
               'type' => $messageType
          ];
     }

     private function nextFlowFromOption($chatBot, $message)
     {
          $currentStepIndex = $chatBot->current_step['index'];

          $nextFlow = $message['next'];
          $typeMessage = $message['type'];
          $current_scene = $chatBot->current_scene;


          $next = null;
          if ($typeMessage !== 'message_helpdesk') {

               $chatBot->update([
                    'current_scene' => $message,
                    'next_scene' => $nextFlow,
                    'current_step' => [
                         'index' => $currentStepIndex + 1,
                         'type' => $typeMessage
                    ],
               ]);

               unset($message['next']);
               $this->chatBotService->message::create([
                    'chatbot_id' => $chatBot->id,
                    'sender' => 'bot',
                    'message' => json_encode($message),
                    'message_type' => "bot_" . $typeMessage,
               ]);
          }


          if ($typeMessage === 'message_helpdesk') {
               $this->chatBotService->message::create([
                    'chatbot_id' => $chatBot->id,
                    'sender' => 'bot',
                    'message' => json_encode($current_scene),
                    'message_type' => "bot_" . $current_scene['type'],
               ]);

               $next = $current_scene;
          }


          return [
               'message' => [
                    'message' => $this->chatBotService->handleMessageOption($message),
                    'message_type' => "bot_" . $typeMessage,
                    'sender' => 'bot',
                    'time' => date('H:i')
               ],
               'next' => $next ? [
                    'message' => $this->chatBotService->handleMessageOption($next),
                    'message_type' => "bot_" . $next['type'],
                    'sender' => 'bot',
                    'time' => date('H:i')
               ] : null,
               'type' => $typeMessage,
          ];
     }

     private function findSelectedOption(&$nextFlow, $current_scene, $message)
     {
          $messageLowerCase = strtolower($message);
          foreach ($current_scene['options'] as $option) {
               if (str_contains(strtolower($option['title']), $messageLowerCase)) {
                    $nextFlow = $option['next'];
                    break;
               }
               // foreach (@$option['keywords'] as $keyword) {
               //      if (str_contains(strtolower($keyword), $messageLowerCase)) {
               //           $nextFlow = $option['next'];
               //           break;
               //      }
               // }
          }
     }

     public function rejectionWithRepeatCurrent($message, $current_scene, $botId)
     {

          $this->chatBotService->message::create([
               'chatbot_id' => $botId,
               'sender' => 'bot',
               'message' => $message,
               'message_type' => 'rejection',
          ]);

          $this->chatBotService->message::create([
               'chatbot_id' => $botId,
               'sender' => 'bot',
               'message' => json_encode($current_scene),
               'message_type' => "bot_" . $current_scene['type'],
          ]);

          return [
               'message' => [
                    'message' => $message,
                    'message_type' => "rejection",
                    'sender' => 'bot',
                    'time' => date('H:i')
               ],
               'next' => [
                    'message' => $this->chatBotService->handleMessageOption($current_scene),
                    'message_type' => "bot_" . $current_scene['type'],
                    'sender' => 'bot',
                    'time' => date('H:i')
               ],
               'type' => "rejection",
          ];
     }

     private function rejectionMessage($message, $botId)
     {

          $this->chatBotService->message::create([
               'chatbot_id' => $botId,
               'sender' => 'bot',
               'message' => $message,
               'message_type' => 'rejection',
          ]);
          return [
               'message' => [
                    'message' => $message,
                    'message_type' => "rejection",
                    'sender' => 'bot',
                    'time' => date('H:i')
               ],
               'type' => "rejection",
          ];
     }

     public function findAllProductSubject($productId = null)
     {
          if (!$productId) {
               return [];
          }

          $subject = DB::table('view_product_subjects')
               ->where('company_product_id', $productId)
               ->where('status', 'active')
               ->select(['id', 'name','parent_id','sorting'])
               ->get();

          $mainSubject = $subject->whereNull('parent_id');
          $subSubject = $subject->whereNotNull('parent_id');
          $subChildSubject = DB::table('company_product_subjects')
               ->whereIn('id', $subSubject->pluck('parent_id')->unique()->values()->toArray())
               ->select(['id', 'name','parent_id','sorting'])
               ->distinct()
               ->get();
          $childSubject = $subChildSubject->whereNotNull('parent_id');
          $subChildSubject = $subChildSubject->whereNull('parent_id');

          return $mainSubject->merge($subChildSubject)->unique()->map(function ($row) use ($subSubject, $childSubject) {
               $subOne = $subSubject->where('parent_id', $row->id);
               $subTwo = $childSubject->where('parent_id', $row->id);
               $row->subs = $subOne->merge($subTwo)->unique()->map(function ($key) use ($subSubject, $childSubject) {
                    $childOne = $subSubject->where('parent_id', $key->id);
                    $childTwo = $childSubject->where('parent_id', $key->id);
                    $key->child = $childOne->merge($childTwo)->unique()->sortBy('sorting')->values()->toArray();
                    return $key;
               })
                    ->sortBy('sorting')
                    ->values()
                    ->toArray();
               return $row;
          })
               ->sortBy('sorting')
               ->values()
               ->toArray();
     }
}