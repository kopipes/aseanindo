<?php

namespace App\Actions\Call;

use App\Enum\ActivityStatus;
use App\Enum\NotificationType;
use App\Helpers\Agora\Agora;
use App\Helpers\BadRequestException;
use App\Helpers\BroadcastNotification;
use App\Helpers\SocketBroadcast;
use App\Models\Call\Call;
use App\Models\Data\User;
use App\Models\Util\Setting;
use App\Services\CallnChat\CallService;
use App\Services\CallnChat\ChatService;
use App\Services\Company\CompanyBillingService;
use App\Services\Company\CompanyHelpdeskService;
use App\Services\Company\CompanyService;
use App\Services\Company\CompanyUserService;
use App\Services\Company\OfficeHourService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InviteCallCustomer
{
     public function __construct(
          private $companyUserService = new CompanyUserService,
          private $companyService = new CompanyService,
          private $companyBillingService = new CompanyBillingService,
          private $companyHelpdeskService = new CompanyHelpdeskService,
          private $officeHourService = new OfficeHourService,
          private $callService = new CallService,
          private $chatService = new ChatService,
     ) {
     }
     public function handle($agentId, $companyId, $customerId, $request)
     {
          $result = null;
          DB::transaction(function () use ($agentId, $companyId, $customerId, $request, &$result) {
               $source = $request->source;
               $phoneNumber = $request->phone_number;
               $sipExtension = $request->sip_extension;
               $sourceCategory = $request->source_category ?: 'inbound';
               $isSip = $request->boolean('is_sip');
               if($isSip && !$request->source_category){
                    $sourceCategory = 'outbound';
               }
               $ticket_id = $request->ticket_id;
               $activeChatAgent = null;
               $billing = $this->companyBillingService->findActiveBilling($companyId);
               $officeHour = $this->officeHourService->findCompanyOfficeHour($companyId);

               if (!$this->companyHelpdeskService->isHelpdeskAvailable($officeHour, $billing, $companyId)) {
                    throw new BadRequestException(__('balance_not_enough'));
               }

               if ($customerId !== 'guest') {
                    $activeChatAgent = $this->chatService->findActiveChatAgent($agentId, $customerId);
                    $activeCallAgent = $this->callService->findActiveCallAgent($agentId, $customerId);
                    if ($activeCallAgent || $activeChatAgent) {
                         throw new BadRequestException('There are calls / chats that you have not ended');
                    }

                    $activeChatCustomer = $this->chatService->findActiveChatIdCustomer($customerId);
                    $activeCallCustomer = $this->callService->findActiveCallCustomer($customerId);
                    if ($activeChatCustomer || $activeCallCustomer) {
                         throw new BadRequestException(__('customer_busy'));
                    }
               }

               /**
                * STEP
                * 1 . Create data into calls table with agora
                * 2.  Check is already chat or not, if chat whit another customer cancel, if chat whit guest and already submit ticket, set gust customer id value same like in chat active table data
                * 3.  If Agent platform is web, send broadcast socket, if android send fcm, if ios send apple push
                * 4.  Update Agent company user status 
                */

               $company = $this->companyService->findById($companyId);
               $customer = User::where('id', $customerId)->where('role', 'customer')->select(['id', 'name', 'profile', 'regid', 'platform'])->first();
               $agent = User::where('id', $agentId)->whereIn('role', ['agent','agent_comm','agent_escalation'])->select(['id', 'name', 'profile', 'regid', 'platform'])->firstOrFail();


               /**
                * Create variable to define properties required to make call whit agora
                */
               $customerName = $customer?->name ?: 'guest';
               $channelName = Str::slug("{$agent->name}_to_{$customerName}", '_')."_".date('His');
               $agentUuid = Agora::createUserId($agentId);
               $customerUuid = Agora::createUserId($customerId === 'guest' ? date('YmdHis') : $customerId);
               $callUuid = Agora::createUserId(now()->getTimestamp());
               $agentToken = Agora::createToken($channelName, $agentUuid);
               $customerToken = Agora::createToken($channelName, $customerUuid);

               /**
                * Step 1 - Create initial call data
                */
               $call = Call::create([
                    'company_id' => $companyId,
                    'agent_id' => $agentId,
                    'customer_id' => $customerId !== 'guest' ? $customerId : null,
                    'is_guest' => false,
                    'channel_name' => $channelName,
                    'agent_token' => $agentToken,
                    'customer_token' => $customerToken,
                    'start_at' => now(),
                    'status' => "ringing",
                    'category' => $request->category ?: 'Outgoing Call',
                    'type' => 'Agent Call Customer',
                    'source' => $source,
                    'customer_uuid' => $customerUuid,
                    'agent_uuid' => $agentUuid,
                    'uuid' => $callUuid,
                    'sip' => $isSip ? 'true' : null,
                    'customer_phone_number' => $phoneNumber,
                    'sip_extension' => $sipExtension,
                    'ticket_id' => $ticket_id,
                    'source_category' => $sourceCategory
               ]);

               /**
                * Step 3 - Send notification to destination
                */
               $call_session = !$isSip ? base64_encode(config('services.agora.app_id')) : null;
               if ($customer) {
                    BroadcastNotification::to($customer)
                         ->channel('incoming-call')
                         ->dispatch([
                              'type' => NotificationType::IncomingCall->value,
                              'title' => 'Incoming Call',
                              'message' => 'You have new incoming call',
                              'call_token' => $customerToken,
                              'call_uuid' => $customerUuid,
                              'channel_name' => $channelName,
                              'call_session' => $call_session,
                              'call_id' => $call->id,
                              'company_id' => $companyId,
                              'user' => [
                                   'id' => $agent->id,
                                   'name' => $agent->name,
                                   'profile' => asset($agent->profile),
                                   'ba_name' => $company->name,
                                   'call_uuid' => $customerUuid,
                              ],
                         ]);
               }

               /**
                * Step 4 - Update Agent company user status 
                */
               $agentStatus = ActivityStatus::Talking;
               if ($activeChatAgent) {
                    $agentStatus = ActivityStatus::CallnChat;
               }
               $this->companyUserService->updateActivityUser($companyId, $agent->id,[
                    'start_activity_at' => $agent->chat_id ? DB::raw('start_activity_at') : now(),
                    'activity' => $agentStatus,
                    'call_id' => $call->id
               ]);

               SocketBroadcast::channel('refresh_agent')
                    ->destination([])
                    ->dispatch([
                         'company_id' => $companyId,
                         'agent_id' => $agent->id,
                         'customer_id' => $customerId,
                    ]);

               $result = [
                    'call_id' => $call->id,
                    'channel_name' => $channelName,
                    'token' => $agentToken,
                    'uuid' => $agentUuid,
                    'company_id' => $companyId,
                    'call_session' => $call_session,
                    'max_ringing' => intval(Setting::findValue('max_ringing_call') ?: 30)
               ];
          });

          return $result;
     }
}