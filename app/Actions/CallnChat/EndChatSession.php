<?php

namespace App\Actions\CallnChat;

use App\Enum\ActivityStatus;
use App\Helpers\BroadcastNotification;
use App\Helpers\SocketBroadcast;
use App\Jobs\CallnChat\CreateRatingAndNotification;
use App\Mail\Chat\SendHistoryChatGuest;
use App\Models\Chat\ChatMessage;
use App\Models\Company\Company;
use App\Models\Company\CompanyProfile;
use App\Models\Company\CompanyUser;
use App\Models\Data\TicketHistory;
use App\Models\Data\User;
use App\Services\CallnChat\CallService;
use App\Services\CallnChat\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EndChatSession
{
     public function __construct(
          private $chatService = new ChatService,
          private $callService = new CallService
     ) {
     }
     public function handle($chatId, $agentId, $customerId, $customerRole = 'guest', $endBy = 'customer')
     {
          DB::transaction(function () use ($chatId, $agentId, $customerId, $customerRole, $endBy) {
               if ($chat = $this->chatService->findChatById($chatId)) {
                    $companyId = $chat->company_id;

                    /**
                     * Find user chat to validate user account
                     */
                    $customerId = $chat->guest_customer_id ?: $customerId;
                    $customerRole = $chat->guest_customer_id ? 'customer' : 'guest';
                    $allChatUser = $this->chatService->findAllActiveChatUser($chatId);
                    $chatCustomer = $allChatUser
                         ->whereIn('user_id', [null, $customerId])
                         ->whereIn('role', ['customer', 'guest'])
                         ->first();
                    $chatAgent = $allChatUser->where('user_id', $agentId)->where('role', 'agent')->first();


                    if ($chatCustomer && $chatAgent) {
                         /**
                          * Find data customer
                          */
                         $customer = User::where('role', 'customer')
                              ->where('id', $chat->guest_customer_id ?: $chatCustomer->user_id)
                              ->first();

                         $agent = User::where('role', 'agent')
                              ->where('id', $agentId)
                              ->first();
                         $company = Company::where('id', $companyId)->first();
                         $acwTimeOut = CompanyProfile::where('company_id', $companyId)->select(['acw_timeout'])->first()?->acw_timeout;

                         /**
                          * Check agent is on call or not
                          */
                         // $activeCallAgent = $this->callService->findActiveCallAgent($agentId);
                         $activeCallAgent = CompanyUser::query()
                              ->where('company_id', $companyId)
                              ->where('user_id', $agentId)
                              ->select(['call_id'])
                              ->first()?->call_id;
                         /**
                          * Update agent activity status
                          */
                         if (!$acwTimeOut) {
                              CompanyUser::query()
                                   ->where('company_id', $companyId)
                                   ->where('user_id', $agentId)
                                   ->update([
                                        'activity' => $activeCallAgent ? ActivityStatus::Talking : 'Online',
                                        'start_activity_at' => $activeCallAgent ? DB::raw('start_activity_at') : null,
                                        'chat_id' => null
                                   ]);
                         }


                         if ($customerRole === 'guest' && $chat->guest_customer_id) {
                              /**
                               * Update chat customer user id into guest customer id
                               */
                              $chatCustomer->update([
                                   'user_id' => $chat->guest_customer_id,
                                   'role' => $chat->guest_customer_id ? 'customer' : 'guest'
                              ]);


                              ChatMessage::where('chat_id', $chat->id)
                                   ->whereNull('user_id')
                                   ->update([
                                        'user_id' => $chat->guest_customer_id
                                   ]);
                         }

                         /**
                          * Create rating and notification into customer and is_active_another_session false
                          */
                         if (!$activeCallAgent && $customer) {
                              CreateRatingAndNotification::dispatchSync($company, $customer, $chatId, 'chat',$agentId);
                         }

                         /**
                          * Send notification into agent and dispatch socket
                          */
                         $broadcastUser = $endBy === 'agent' ? $customer : $agent;
                         if (!$broadcastUser && $endBy === 'agent') {
                              $broadcastUser = (object) [
                                   'platform' => 'web',
                                   'id' => $chatId
                              ];
                         }

                         if ($chat->guest_customer_id !== null) {
                              SocketBroadcast::channel('chat-ended')
                                   ->destination([$chat->id])
                                   ->send([
                                        'type' => 'end_chat',
                                        'title' => 'Chat Ended',
                                        'message' => 'Chat Ended',
                                        'chat_id' => $chatId,
                                        'end_at' => date('Y-m-d H:i:s')
                                   ]);
                              if ($customer) {
                                   $ticket = TicketHistory::query()
                                        ->join('tickets', 'tickets.id', 'ticket_histories.ticket_id')
                                        ->where('ticket_histories.chat_id', $chat->id)
                                        ->select('tickets.customer_name')
                                        ->first();
                                   // Send history chat to guest email

                                   $customerName = $ticket ? $ticket->customer_name : $customer->name;
                                   Mail::to($customer->email)
                                        ->send(
                                             new SendHistoryChatGuest(
                                                  $customerName,
                                                  $chatId,
                                                  $companyId
                                             )
                                        );
                              }
                         }


                         BroadcastNotification::to($broadcastUser)
                              ->channel('chat-ended')
                              ->dispatch([
                                   'type' => 'end_chat',
                                   'title' => 'Chat Ended',
                                   'message' => 'Chat Ended',
                                   'chat_id' => $chatId,
                                   'end_at' => date('Y-m-d H:i:s')
                              ]);

                         $chatAgent->update(['end_at' => now()]);
                         $chatCustomer->update(['end_at' => now()]);
                    }

                    /**
                     * Update Chat Status
                     */
                    $chat->update([
                         'status' => 'end',
                         'end_at' => now()
                    ]);

                    SocketBroadcast::channel('refresh_agent')
                         ->destination([])
                         ->dispatch([
                              'company_id' => $companyId,
                              'agent_id' => null,
                              'customer_id' => $customerId,
                         ]);
               }
          });
     }
}