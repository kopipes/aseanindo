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

class InviteCallAgent
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
     public function handle($agentId, $companyId, $user)
     {
          $result = null;
          DB::transaction(function () use ($agentId, $companyId, $user, &$result) {
               $phoneNumber = request('phone_number');
               $extensionCode = request('extension_code');
               $pstnId = request('pstn_id');
               $billing = $this->companyBillingService->findActiveBilling($companyId);
               $officeHour = $this->officeHourService->findCompanyOfficeHour($companyId);
               $userActivity = $user;
               $chatId = request('chat_id');
               if($chatId){
                    $chat = $this->chatService->findChatById($chatId);
                    if($chat){
                         $userId = $chat->customer_id ?: $chat->guest_customer_id;
                         $userCustomer = User::query()
                              ->leftJoin('company_customer_contacts',function($join) use($companyId){
                                   $join->on('company_customer_contacts.customer_id','users.id');
                                   $join->where('company_customer_contacts.company_id',$companyId);
                              })
                              ->where('users.role', 'customer')
                              ->where('users.id', $userId)
                              ->select([
                                   'users.id',
                                   DB::raw("IFNULL(users.name,company_customer_contacts.name) as name"), 
                                   'users.role',
                                   'users.profile'
                              ])
                              ->first();
                         if($userCustomer) {
                              $userActivity = $userCustomer;
                         }
                    }
               }
               $is_pstn = @$user?->is_pstn ?: false;

               if (!$this->companyHelpdeskService->isHelpdeskAvailable($officeHour, $billing, $companyId)) {
                    throw new BadRequestException(__('agent_not_available'));
               }
               /**
                * This is validation, you should know
                */
               if (!$agent = $this->companyUserService->findActiveAgentById($companyId, $agentId)) {
                    throw new BadRequestException(__('agent_not_available'));
               }
               /**
                * This is validation to, you should know
                */
               if ($agent->activity === ActivityStatus::Talking) {
                    throw new BadRequestException(__('agent_not_available'));
               }


               $activeChatAgent = $this->chatService->findActiveChatAgent($agentId, $userActivity?->role != 'guest' ? $userActivity?->id : null);
               $activeCallAgent = $this->callService->findActiveCallAgent($agentId, $userActivity?->role != 'guest' ? $userActivity->id : null);

               if ($activeCallAgent || $activeChatAgent) {
                    throw new BadRequestException(__('agent_not_available'));
               }

               /**
                * STEP
                * 1 . Create data into calls table with agora
                * 2.  Check is already chat or not, if chat whit another customer cancel, if chat whit guest and already submit ticket, set gust customer id value same like in chat active table data
                * 3.  If Agent platform is web, send broadcast socket, if android send fcm, if ios send apple push
                * 4.  Update Agent company user status 
                */

               $guestCustomerId = null;
               $userAgent = $agent->user;
               $company = $this->companyService->findById($companyId);

               if ($user->role === 'guest') {
                    $guestCustomerId = $activeChatAgent ? $activeChatAgent->guest_customer_id : null;
               }

               /**
                * Create variable to define properties required to make call whit agora
                */
               $channelName = Str::slug("{$user->name}_to_{$userAgent->name}", '_')."_".date('His');
               $agentUuid = Agora::createUserId($userAgent->id);
               $customerUuid = Agora::createUserId($user->id);
               $callUuid = Agora::createUserId(now()->getTimestamp());
               $agentToken = Agora::createToken($channelName, $agentUuid);
               $customerToken = Agora::createToken($channelName, $customerUuid);

               /**
                * Step 1 - Create initial call data
                */
               $call = Call::create([
                    'company_id' => $companyId,
                    'agent_id' => $userAgent->id,
                    'customer_id' => $user->role === 'customer' ? $user->id : null,
                    'is_guest' => $user->role === 'guest' ? true : false,
                    'channel_name' => $channelName,
                    'agent_token' => $agentToken,
                    'customer_token' => $customerToken,
                    'start_at' => now(),
                    //  if user accept, update start at value
                    'status' => "ringing",
                    'category' => 'Incoming Call',
                    'type' => 'Customer Call Agent',
                    'source' => $user->role === 'guest' ? 'From App' : 'From Web',
                    'customer_uuid' => $customerUuid,
                    'agent_uuid' => $agentUuid,
                    'uuid' => $callUuid,
                    'guest_customer_id' => $guestCustomerId,
                    'sip' => $extensionCode ? true : null,
                    'sip_extension' => $extensionCode,
                    'customer_phone_number' => $phoneNumber,
                    'pstn_id' => $pstnId
               ]);

               /**
                * Step 3 - Send notification to destination
                */
               $call_session = base64_encode(config('services.agora.app_id'));
               if(!$is_pstn){
                    BroadcastNotification::to($userAgent)
                         ->channel('incoming-call')
                         ->dispatch([
                              'type' => NotificationType::IncomingCall->value,
                              'title' => 'Incoming Call',
                              'message' => 'You have new incoming call',
                              'call_token' => $agentToken,
                              'call_uuid' => $agentUuid,
                              'channel_name' => $channelName,
                              'call_session' => $call_session,
                              'call_id' => $call->id,
                              'company_id' => $companyId,
                              'user' => [
                                   'id' => $userActivity?->id,
                                   'name' => $userActivity?->name,
                                   'profile' => asset($userActivity?->profile),
                                   'ba_name' => $company->name,
                                   'call_uuid' => $customerUuid,
                                   'phone_number' => $phoneNumber
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
               $agent->update([
                    'start_activity_at' => $agent->chat_id ? DB::raw('start_activity_at') : now(),
                    'activity' => $agentStatus,
                    'call_id' => $call->id
               ]);

               SocketBroadcast::channel('refresh_agent')
                    ->destination([])
                    ->dispatch([
                         'company_id' => $companyId,
                         'agent_id' => $userAgent->id,
                         'customer_id' => $user->role === 'customer' ? $user->id : $guestCustomerId,
                    ]);

               $result = [
                    'call_id' => $call->id,
                    'channel_name' => $channelName,
                    'token' => $customerToken,
                    // if trigger call by agent, send agentToken
                    'uuid' => $customerUuid,
                    'company_id' => $companyId,
                    'call_session' => $call_session,
                    'max_ringing' => intval(Setting::findValue('max_ringing_call') ?: 30)
               ];
          });

          return $result;
     }
}