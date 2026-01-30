<?php

namespace App\Actions\Call;

use Carbon\Carbon;
use App\Models\Data\User;
use App\Models\Data\Ticket;
use Illuminate\Support\Str;
use App\Enum\ActivityStatus;
use App\Helpers\Agora\Agora;
use App\Models\Util\Setting;
use Illuminate\Http\Request;
use App\Helpers\SocketBroadcast;
use App\Models\Data\Notification;
use App\Models\Util\SipExtension;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;
use App\Models\Company\CompanyUser;
use App\Helpers\BadRequestException;
use App\Helpers\CallRecordingSystem;
use App\Helpers\BroadcastNotification;
use App\Services\CallnChat\CallService;
use App\Services\CallnChat\ChatService;
use App\Services\Company\CompanyService;
use App\Services\CallnChat\CallLogService;
use App\Models\Data\CompanyContactCustomer;
use App\Services\Company\CompanyUserService;
use App\Services\Company\CompanyBillingService;
use App\Jobs\CallnChat\CreateRatingAndNotification;
use App\Services\CallnChat\CallbackCallRequestService;

class CallActionTriggered
{
     public function __construct(
          private $callService = new CallService,
          private $companyService = new CompanyService,
          private $userService = new UserService,
          private $companyBillingService = new CompanyBillingService,
          private $companyUserService = new CompanyUserService,
          private $callLogService = new CallLogService,
          private $chatService = new ChatService,
          private $callbackCallRequestService = new CallbackCallRequestService
     ) {
     }

     public function handle(Request $request, $actionBy, $action, $callId, $userLoginId = null)
     {
          // DB::transaction(function () use ($request, $actionBy, $action, $callId, $userLoginId) {
          $action = $action === 'cancel' ? 'missed' : $action;
          $notAllowedCallStatus = ['end', 'missed', 'reject', 'disconnect'];

          if (!$call = $this->callService->findCallById($callId)) {
               throw new BadRequestException('Call not found');
          }

          if (in_array($call->status, $notAllowedCallStatus)) {
               throw new BadRequestException($this->getMessageByCallStatus($call->status));
          }

          $companyId = $call->company_id;
          $company = $this->companyService->findById($companyId);
          $customer = $this->userService->findCustomer($call->guest_customer_id ?: $call->customer_id);
          $agent = $this->userService->findAgent($call->agent_id);
          $billing = $this->companyBillingService->findActiveBilling($companyId);

          switch ($action) {
               case 'accept':
                    $this->acceptCall($billing, $company, $customer, $call);
                    break;
               case 'reject':
                    $this->rejectCall($actionBy, $call, $company, $customer, $agent, $userLoginId);
                    break;
               case 'missed':
                    $this->missedOrCancelCall($actionBy, $call, $company, $customer, $agent);
                    break;
               case 'disconnect':
                    $this->disconnectCall($request, $actionBy, $billing, $company, $customer, $call, $agent, $userLoginId);
                    break;
               case 'invite-spv':
                    $this->inviteSpv($billing, $call, $request->spv_id, $request->category);
                    break;
               case 'spv-join':
                    $this->spvJoin($billing, $call, $userLoginId);
                    break;
               case 'spv-leave':
                    $this->spvLeave($billing, $call, $userLoginId);
                    break;
               case 'spv-reject':
                    $this->spvReject($call);
                    break;
               case 'end':
                    $this->endCall($request, $actionBy, $billing, $company, $customer, $agent, $call);
                    break;
               default:
                    break;
          }
          // });
     }


     private function acceptCall($billing, $company, $customer, $call)
     {

          $totalSpv = $billing->agent_spv_limit + $billing->comm_agent_limit;
          $minimumBalance = @$billing->summary['additional']['additional_in_app']['minimum'];
          $minimumBalance = $minimumBalance ?: intval(Setting::findValue('minimum_balance') ?: 10000);
          $minimumBilling = ($minimumBalance * $totalSpv);

          if (!$billing || $billing?->balance <= $minimumBilling) {
               throw new BadRequestException(__('balance_not_enough'));
          }

          // If call category is Callback, then delete callback data from log
          if ($call->category === 'Callback' && $customer) {
               $this->callbackCallRequestService->deleteCustomerCallbackRequest($customer->id, $company->id);
          }

          // Update Call status into talking
          $call->update([
               'start_at' => now(),
               'status' => 'talking'
          ]);

          // Update user activity status
          $companyAgent = $this->companyUserService->findCompanyUser($company->id, $call->agent_id);
          if (!$companyAgent->chat_id) {
               $companyAgent->update(['start_activity_at' => now()]);
          }

          // Start agora record session
          if (!$call->sip) {
               CallRecordingSystem::startAgoraRecording($company->name, $call);
          }

          //  insert notification into customer
          if ($customer) {
               $title = $call->type === 'Customer Call Agent' ? 'Outgoing Call' : 'Incoming Call';
               $description = $call->type === 'Customer Call Agent' ? 'Anda telah menghubungi help desk ' : 'Anda telah dihubungi help desk ';
               Notification::create([
                    'company_id' => $company->id,
                    'user_id' => $customer?->id,
                    'user_role' => 'customer',
                    'category' => 'call_history',
                    'title' => $title,
                    'description' => $description . $company->name,
                    'type' => $title,
                    'parent_id' => $call->id,
               ]);
               // delete call history Missed Call by customer and agent id
               $this->callService->deleteMissedCallByCustomerAndAgent($customer?->id, $call->agent_id);
          }

          SocketBroadcast::channel('refresh_agent')
               ->destination([])
               ->dispatch([
                    'company_id' => $company->id,
                    'agent_id' => $call->agent_id,
                    'customer_id' => $customer?->id,
               ]);

          // Create call logs histories
          $this->callLogService->createNewLogs(
               call: $call,
               companyId: $company->id,
               customerId: $customer?->id,
          );

          // Todo : Broadcast remaining call duration available
     }


     private function rejectCall($actionBy, $call, $company, $customer, $agent, $userLoginId)
     {
          // Update call status and another properties
          $call->update([
               'status' => 'reject',
               'end_at' => now()
          ]);

          // Update user activity status
          $this->updateAgentActivity($call->agent_id, $company->id, $call->customer_id);

          //  insert notification into customer
          if ($customer) {
               $title = $call->type === 'Customer Call Agent' ? 'Outgoing Missed Call' : 'Incoming Missed Call';
               $description = $call->type === 'Customer Call Agent' ? 'Anda telah menghubungi help desk ' : 'Anda telah dihubungi help desk ';
               Notification::create([
                    'company_id' => $company->id,
                    'user_id' => $customer?->id,
                    'user_role' => 'customer',
                    'category' => 'call_history',
                    'title' => $title,
                    'description' => $description . $company->name,
                    'type' => $title,
                    'parent_id' => $call->id,
               ]);
          }



          $userBroadcast = $actionBy == 'agent' ? $customer : $agent;
          if (!$userBroadcast && $actionBy == 'agent') {
               $userBroadcast = (object) [
                    'platform' => 'web',
                    'id' => $call->id
               ];
          }
          BroadcastNotification::to($userBroadcast)
               ->channel('call-rejected')
               ->dispatch([
                    'type' => 'rejected_call',
                    'title' => 'Call Rejected',
                    'message' => 'Call Rejected',
                    'call_id' => $call->id,
               ]);
     }


     private function missedOrCancelCall($actionBy, $call, $company, $customer, $agent)
     {
          // Update call status and another properties
          $call->update([
               'status' => 'missed',
               'category' => 'Missed Call',
          ]);

          // Update user activity status
          $this->updateAgentActivity($call->agent_id, $company->id, $call->customer_id, true);

          //  insert notification into customer
          if ($customer) {
               $title = $call->type === 'Customer Call Agent' ? 'Outgoing Missed Call' : 'Incoming Missed Call';
               $description = $call->type === 'Customer Call Agent' ? 'Anda telah menghubungi help desk ' : 'Anda telah dihubungi help desk ';
               Notification::create([
                    'company_id' => $company->id,
                    'user_id' => $customer?->id,
                    'user_role' => 'customer',
                    'category' => 'call_history',
                    'title' => $title,
                    'description' => $description . $company->name,
                    'type' => $title,
                    'parent_id' => $call->id,
               ]);
          }


          $userBroadcast = $actionBy == 'agent' ? $customer : $agent;
          if (!$userBroadcast && $actionBy == 'agent') {
               $userBroadcast = (object) [
                    'platform' => 'web',
                    'id' => $call->id
               ];
          }
          BroadcastNotification::to($userBroadcast)
               ->channel('call-missed')
               ->dispatch([
                    'type' => 'missed_call',
                    'title' => 'Missed Call',
                    'message' => 'Missed Call',
                    'call_id' => $call->id,
               ]);
     }


     private function disconnectCall($request, $actionBy, $billing, $company, $customer, $call, $agent, $userLoginId)
     {

          $this->endCall($request, $actionBy, $billing, $company, $customer, $agent, $call, 'disconnect');


          // Update user activity status
          $this->updateAgentActivity($call->agent_id, $company->id, $call->customer_id, true);

          $userBroadcast = $actionBy == 'agent' ? $customer : $agent;
          if (!$userBroadcast && $actionBy == 'agent') {
               $userBroadcast = (object) [
                    'platform' => 'web',
                    'id' => $call->id
               ];
          }
          BroadcastNotification::to($userBroadcast)
               ->channel('call-disconnect')
               ->dispatch([
                    'type' => 'disconnect_call',
                    'title' => 'Call Disconnect',
                    'message' => 'Call Disconnect',
                    'call_id' => $call->id,
               ]);
     }


     private function endCall($request, $actionBy, $billing, $company, $customer, $agent, $call, $status = 'end')
     {
          $isSip = $call->sip;
          $spvId = $call->spv_id;
          $leaveAt = now();
          $ivrCallDuration = 0;
          // Calculate data to collect price duration,and more

          if (!$isSip) {
               //  agora end record and get record url
               CallRecordingSystem::stopAgoraRecording($call);
          } else {
               // SIP get recording data
               $agentExtension = $request->agent_extension;
               $customerPhone = $request->destination;
               CallRecordingSystem::endSipCall($call, $agentExtension, $customerPhone);
               $ivrCallDuration = CallRecordingSystem::getIVRSipDuration($call, $agentExtension, $customerPhone);
          }

          $this->endCallLog($billing, $call, $leaveAt, $ivrCallDuration);
          // Update user activity status
          $this->updateAgentActivity($call->agent_id, $company->id, $call->guest_customer_id ?: $call->customer_id);

          $tempSpvId = $call->temp_spv_id;
          if ($tempSpvId) {
               SocketBroadcast::channel('call-spv-reject')
                    ->destination([$tempSpvId])
                    ->send([
                         'reject_at' => now(),
                    ]);
          }



          $calLog = $this->callLogService->findCallLogCostDuration($call);
          // Update call status and another properties
          $call->update([
               'status' => $status,
               'end_at' => $leaveAt,
               'total_duration' => $calLog->duration,
               'total_cost' => $calLog->cost,
               'customer_id' => $call->guest_customer_id ?: $call->customer_id,
               'spv_id' => null,
               'temp_spv_id' => null
          ]);

          // If customer not empty, send create rating and notification
          if ($customer) {
               CreateRatingAndNotification::dispatchSync($company, $customer, $call->id, 'call', $call->agent_id);
          }


          $userBroadcast = $actionBy == 'agent' ? $customer : $agent;
          if (!$userBroadcast && $actionBy == 'agent') {
               $userBroadcast = (object) [
                    'platform' => 'web',
                    'id' => $call->id
               ];
          }

          if ($call->guest_customer_id !== null) {
               SocketBroadcast::channel('call-ended')
                    ->destination([$call->id])
                    ->send([
                         'title' => 'Call End',
                         'message' => 'Call End',
                         'call_id' => $call->id,
                    ]);
          }

          if ($spvId !== null) {
               if ($spv = User::select(['id', 'platform', 'regid'])->where('id', $spvId)->first()) {
                    BroadcastNotification::to($spv)
                         ->channel('call-ended')
                         ->dispatch([
                              'type' => 'call_end',
                              'title' => 'Call End',
                              'message' => 'Call End',
                              'call_id' => $call->id,
                         ]);
               }
          }

          if ($spvId) {
               $this->forceAvailableSpvExtension($spvId);
          }

          BroadcastNotification::to($userBroadcast)
               ->channel('call-ended')
               ->dispatch([
                    'type' => 'call_end',
                    'title' => 'Call End',
                    'message' => 'Call End',
                    'call_id' => $call->id,
               ]);

          SocketBroadcast::channel('refresh_agent')
               ->destination([])
               ->dispatch([
                    'company_id' => $call->company_id,
                    'agent_id' => $call->agent_id,
                    'customer_id' => $call->guest_customer_id ?: $call->customer_id,
               ]);
     }

     public function inviteSpv($billing, $call, $spvId, $category)
     {
          $totalSpv = $billing->agent_spv_limit + $billing->comm_agent_limit;
          $minimumBalance = @$billing->summary['additional']['additional_in_app']['minimum'];
          $minimumBalance = $minimumBalance ?: intval(Setting::findValue('minimum_balance') ?: 10000);
          $minimumBilling = ($minimumBalance * $totalSpv);

          if (!$billing || $billing->balance <= $minimumBilling) {
               throw new BadRequestException('Your company balance is not enough');
          }

          $spvCompany = CompanyUser::query()
               ->where('company_id', $call->company_id)
               ->where('user_id', $spvId)
               ->first();
          if($spvCompany->activity==ActivityStatus::Talking){
               throw new BadRequestException("The spv is not available now. Please try again later.");
          }

          $customer = CompanyContactCustomer::query()
               ->where('customer_id', $call->customer_id ?: $call->guest_customer_id)
               ->where('company_id', $call->company_id)
               ->select(['customer_id', 'name'])
               ->first();
          $customerId = $customer?->customer_id;
          $customerName = $customer?->name;
          if ($call?->ticket_id) {
               $ticket = Ticket::query()
                    ->where('id', $call->ticket_id)
                    ->select(['customer_name'])
                    ->first();
               $customerName = $ticket?->customer_name;
          }

          if ($call->sip && (!$call->customer_id || $call->customer_id == 'guest')) {
               $customerId = base64_encode($customerName);
          }

          $spv = User::query()
               ->where('id', $spvId)
               ->select(['id', 'platform', 'regid'])
               ->first();

          $agent = User::query()
               ->leftJoin('company_users as cp', function ($join) use ($call) {
                    $join->on('cp.user_id', 'users.id');
                    $join->where('cp.company_id', $call->company_id);
               })
               ->where('users.id', $call->agent_id)
               ->select('cp.profile', 'cp.name', 'users.id')
               ->first();
          if ($agent) {
               $agent->profile = asset($agent->profile);
          }
          $call->update([
               'temp_spv_id' => $spvId
          ]);

          $customerName = $customerName ?: $call?->customer_phone_number;

          $uuid = Agora::createUserId($spvId);
          $token = Agora::createToken($call->channel_name, $uuid);
          $call_session = base64_encode(config('services.agora.app_id'));
          BroadcastNotification::to($spv)
               ->channel('incoming-call')
               ->dispatch([
                    'type' => 'incoming_call',
                    'title' => 'Incoming Call',
                    'message' => 'Incoming Call',
                    'source' => $call->sio ? 'sip' : 'agora',
                    'agent' => $agent,
                    'category' => $category,
                    // spying | coaching
                    'call' => [
                         'id' => $call->id,
                         'source_category' => $call->source_category,
                         'customer_id' => $customerId,
                         'customer_name' => $customerName ?: 'Guest',
                         'connection' => [
                              'channel' => $call->channel_name,
                              'token' => $token,
                              'uuid' => $uuid,
                              'session' => $call_session,
                         ],
                         'sip' => null
                    ]
               ]);
     }


     private function spvJoin($billing, $call, $spvId)
     {
          $totalSpv = $billing->agent_spv_limit + $billing->comm_agent_limit;
          $minimumBalance = @$billing->summary['additional']['additional_in_app']['minimum'];
          $minimumBalance = $minimumBalance ?: intval(Setting::findValue('minimum_balance') ?: 10000);
          $minimumBilling = ($minimumBalance * $totalSpv);

          if (!$billing || $billing->balance <= $minimumBilling) {
               throw new BadRequestException('Your company balance is not enough');
          }

          $call->update(['spv_id' => $spvId]);
          $this->callLogService->startSpvLog($call, $spvId);

          $spv = $this->userService->findSpvCompany($spvId, $call->company_id);
          CompanyUser::query()
               ->where('company_id', $call->company_id)
               ->where('user_id', $spvId)
               ->update([
                    'start_activity_at' => now(),
                    'activity' => ActivityStatus::Talking,
                    'call_id' => $call->id
               ]);
          SocketBroadcast::channel('call-spv-joined')
               ->destination([$call->id])
               ->send([
                    'join_at' => now(),
                    'name' => $spv->name,
                    'profile' => asset($spv->profile),
                    'id' => $spv->id
               ]);
     }

     private function spvReject($call)
     {
          $spvId = $call->temp_spv_id ?: '-..---...';
          SocketBroadcast::channel('call-spv-reject')
               ->destination([$call->id, $spvId])
               ->send([
                    'reject_at' => now(),
               ]);

          $call->update([
               'spv_id' => null,
               'temp_spv_id' => null
          ]);
     }


     private function spvLeave($billing, $call, $spvId)
     {
          $call->update([
               'spv_id' => null,
               'temp_spv_id' => null
          ]);

          $spvJoinLog = $this->callLogService->findSpvLogJoinCall($call, $spvId);
          if ($spvJoinLog && !$spvJoinLog->leave_at) {
               $spvJoinAt = $spvJoinLog->join_at;

               // Calculate data to collect price duration,and more
               list(
                    'price' => $price,
                    'duration' => $duration,
                    'leave_at' => $leaveAt,
                    'total_price' => $total_price,
                    'call_cost' => $call_cost
               ) = $this->calculateCostAndDuration($billing, $spvJoinAt, $call->sip, true);

               $spvJoinLog->update([
                    'leave_at' => $leaveAt,
                    'duration' => $duration,
                    'cost_per_second' => $call_cost,
                    'cost' => $price,
               ]);
          }

          CompanyUser::query()
               ->where('company_id', $call->company_id)
               ->where('user_id', $spvId)
               ->update([
                    'start_activity_at' => null,
                    'activity' => 'Online',
                    'call_id' => null
               ]);
          // UPDATE AVAILABLE EXTENSION
          $this->forceAvailableSpvExtension($spvId);

          SocketBroadcast::channel('call-spv-leave')
               ->destination([$call->id])
               ->send([
                    'leave_at' => now(),
               ]);
     }


     private function updateAgentActivity($agentId, $companyId, $customerId, $disconnect = false)
     {
          // Update user activity status
          $activeChatAgent = $this->chatService->findActiveChatAgentWithCustomer($agentId, $customerId);
          $activity = $activeChatAgent ? ActivityStatus::Chatting : ActivityStatus::Talking;
          $startActivity = $activeChatAgent ? DB::raw('start_activity_at') : null;
          if ($disconnect) {
               $activity = ActivityStatus::Online;
               $startActivity = null;
          }
          $this->companyUserService->updateActivityUser($companyId, $agentId, [
               'activity' => $activity,
               'start_activity_at' => $startActivity,
               'call_id' => null,
          ]);

          SocketBroadcast::channel('refresh_agent')
               ->destination([])
               ->dispatch([
                    'company_id' => $companyId,
                    'agent_id' => $agentId,
                    'customer_id' => $customerId,
               ]);
     }


     private function forceAvailableSpvExtension($spvId)
     {
          SipExtension::query()
               ->where('agent_id', $spvId)
               ->update([
                    'agent_id' => null,
                    'is_available' => 1
               ]);
     }
     private function endCallLog($billing, $call, $endAt, $ivrCallDuration)
     {
          $isSip = $call->sip;
          $isPstn = $call->pstn_id;
          $isUseMinutes = $isPstn ? true : false;
          $callDestionation = @$call->sip_extension['destination'];
          $keyCost = $isPstn ? 'incoming_sip_price' : 'sip_call';

          $threeDigit = substr($callDestionation, 0, 3);
          $fourDigit = substr($callDestionation, 0, 4);
          if ($threeDigit == '021' || in_array($fourDigit, ['1500'])) {
               $keyCost = 'telco_1500';
               $isUseMinutes = true;
          }

          if (!$isSip) {
               $keyCost = 'in_app_call';
               $isUseMinutes = false;
          }


          $sipCallCost = DB::table('cms_base_value')->where('key', $keyCost)->select(['value'])->first()?->value ?: 11;

          $callLogs = $this->callLogService->findAllOnGoingLogs($call->id);

          foreach ($callLogs as $log) {
               $balance = $billing->balance;
               $callCost = $billing->call_cost;
               $logRole = $log->role;
               if ($isSip) {
                    $callCost = intval($sipCallCost);
                    if ($log->role === 'spv') {
                         $callCost = $billing->extension_call_cost;
                    }
               }
               $callCost = $callCost ?: 3.5;
               $startAt = $log->join_at;

               $callDuration = 0;
               if ($startAt) {
                    $endAt = $isSip ? $endAt : now()->addSeconds(5);
                    $startCallAt = Carbon::parse($startAt);
                    $callDuration = $endAt->diffInSeconds($startCallAt);
               }

               if ($isSip && $logRole !== 'spv') {
                    $callDuration += $ivrCallDuration;
               }

               $totalPriceCost = $callCost * $callDuration;
               if ($isUseMinutes) {
                    if ($callDuration <= 60) {
                         $totalPriceCost = $callCost;
                    } else {
                         $durationMinutes = ceil($callDuration / 60);
                         $totalPriceCost = $callCost * $durationMinutes;
                    }
               }

               $currentBalance = $balance - $totalPriceCost;

               if (is_nan($totalPriceCost)) {
                    $totalPriceCost = 0;
                    $currentBalance = $balance;
               }

               if (($isSip && !in_array($logRole, ['customer', 'guest'])) || !$isSip) {
                    $billing->update([
                         'balance' => $currentBalance,
                         'balance_usage' => $billing->balance_usage + $currentBalance
                    ]);
               }

               $log->update([
                    'leave_at' => $endAt,
                    'duration' => $callDuration,
                    'cost_per_second' => $callCost,
                    'cost' => $totalPriceCost
               ]);
          }
     }
     private function calculateCostAndDuration($billing, $startAt = null, $isSip = null, $isSpv = false)
     {
          $balance = $billing->balance;
          $callCost = !$isSip ? $billing->call_cost : $billing->extension_call_cost;
          $callCost = $callCost ?: 3.5;

          $endAt = now();
          $callDuration = 0;
          if ($startAt) {
               $endAt = $isSip ? $endAt : now()->addSeconds(5);
               $startCallAt = Carbon::parse($startAt);
               $callDuration = $endAt->diffInSeconds($startCallAt);
          }

          $priceCost = $callCost * $callDuration;
          $totalPriceCost = $priceCost;
          $currentBalance = $balance - $totalPriceCost;

          if (is_nan($totalPriceCost)) {
               $totalPriceCost = 0;
               $currentBalance = $balance;
          }

          $billing->update([
               'balance' => $currentBalance,
               'balance_usage' => $billing->balance_usage + $currentBalance
          ]);

          return [
               'price' => $priceCost,
               'total_price' => $totalPriceCost,
               'duration' => $callDuration,
               'leave_at' => $endAt,
               'call_cost' => $callCost
          ];
     }

     private function getMessageByCallStatus($status)
     {
          $messages = [
               'end' => 'The call has ended !',
               'missed' => 'The call has been cancelled!',
               'reject' => 'The call has been cancelled!',
               'disconnect' => "Calls can't be connected!"
          ];

          return @$messages[$status];
     }
}