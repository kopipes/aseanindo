<?php

namespace App\Actions\CallnChat;

use App\Helpers\FCM;
use App\Helpers\Yellow;
use App\Models\Call\Call;
use App\Models\Chat\Chat;
use App\Models\Data\User;
use Illuminate\Support\Str;
use App\Enum\ActivityStatus;
use Illuminate\Http\Request;
use App\Helpers\SocketBroadcast;
use App\Models\Chat\ChatMessage;
use Illuminate\Support\Facades\DB;
use App\Models\Company\CompanyUser;
use App\Helpers\BadRequestException;
use App\Services\CallnChat\CallService;
use App\Services\CallnChat\ChatService;
use App\Services\Company\CompanyUserService;

class SendMessage
{
     public function __construct(
          private $chatService = new ChatService,
          private $callService = new CallService,
          private $companyUserService = new CompanyUserService,
     ) {
     }
     public function handle(Request $request, $userId, $category, $companyId)
     {
          $result = null;
          DB::transaction(function () use (&$result, $request, $userId, $category, $companyId) {
               $chatId = $request->chat_id;
               $destination = $request->destination;
               $message = $request->message;
               $guestCustomerId = null;



               $activeCallAgent = null;

               if (!$chatId) {

                    // Check agent status is on call or chat when first send message
                    $companyUser = CompanyUser::query()
                         ->where('user_id', $destination)
                         ->where('company_id', $companyId)
                         ->select(['activity'])
                         ->first();
                    $activeCallAgent = Call::query()
                         ->where('company_id', $companyId)
                         ->where('agent_id', $destination)
                         ->where('status', 'talking')
                         ->first();
                    if ((!$companyUser && $companyUser?->activity != ActivityStatus::Online) || ($activeCallAgent && $activeCallAgent?->customer_id != $userId)) {
                         throw new BadRequestException(__('agent_not_available'));
                    }

                    $activeChatAgent = $this->chatService->findActiveChatByAgentId($destination);
                    // $activeCallAgent = $this->callService->findOneActiveCallAgent($destination);
                    if ($activeChatAgent || ($activeCallAgent && $activeCallAgent->customer_id !== $userId)) {
                         throw new BadRequestException(__('agent_not_available'));
                    }
                    $guestCustomerId = $activeCallAgent ? $activeCallAgent->guest_customer_id : null;

               }

               $chat = $this->chatService->findOrCreateNewChat(
                    chatId: $chatId,
                    companyId: $companyId,
                    destination: $destination,
                    userId: $userId,
                    guestId: $guestCustomerId
               );
               if (!$chatId) {
                    $agentStatus = ActivityStatus::Chatting;
                    if ($activeCallAgent) {
                         $agentStatus = ActivityStatus::CallnChat;
                    }
                    $this->companyUserService->updateActivityUser($companyId, $destination, [
                         'activity' => $agentStatus,
                         'chat_id' => $chat->id,
                         'start_activity_at' => $activeCallAgent ? DB::raw('start_activity_at') : now()
                    ]);
               }

               $result = null;
               switch ($category) {
                    case 'message':
                         $result = $this->sendChatMessage($chat, $companyId, $category, $userId, $message);
                         break;
                    case 'location':
                         $address = @$message['address'];
                         if (!@$message['address']) {
                              $message['address'] = '';
                         }
                         $result = $this->sendChatMessage($chat, $companyId, $category, $userId, json_encode($message), $address ?: 'Location');
                         break;
                    case 'file':
                         $file = $request->file('message');
                         $result = $this->sendChatFile($chat, $companyId, $userId, $file);
                         break;
               }

               if (!$chatId) {
                    $this->sendNotificationToAgent($companyId, $destination, $userId, $result);
               }


          });
          return $result;
     }

     private function sendChatMessage(Chat $chat, $companyId, $category, $userId, $message, $messageContent = null)
     {
          $chatMessage = ChatMessage::create([
               'chat_id' => $chat->id,
               'user_id' => $userId,
               'message_type' => $category ?: 'message',
               'message' => $message,
               'company_id' => $companyId
          ]);


          $chat->update([
               'last_chat' => $messageContent ?: $message,
               'last_chat_by' => $userId,
               'last_chat_role' => $userId ? 'customer' : 'guest',
               'updated_at' => now()
          ]);
          return [
               'chat_id' => $chat->id,
               'chat_message_id' => $chatMessage->id
          ];
     }


     private function sendChatFile(Chat $chat, $companyId, $userId, $file)
     {
          $messageContent = [
               'url' => asset(Yellow::uploadFile($file)),
               'name' => $file->getClientOriginalName(),
               'format' => $file->getClientOriginalExtension(),
               'size' => Yellow::formatBytes($file->getSize()),
          ];
          $chatMessage = ChatMessage::create([
               'chat_id' => $chat->id,
               'user_id' => $userId,
               'message_type' => 'file',
               'message' => json_encode($messageContent),
               'company_id' => $companyId
          ]);

          $chat->update([
               'last_chat' => $file->getClientOriginalName(),
               'last_chat_by' => $userId,
               'last_chat_role' => $userId ? 'customer' : 'guest',
               'updated_at' => now()
          ]);
          return [
               'chat_id' => $chat->id,
               'chat_message_id' => $chatMessage->id,
               ...$messageContent
          ];
     }


     private function sendNotificationToAgent($companyId, $destination, $userId, $resultMessage)
     {
          $agent = User::where('id', $destination)
               ->whereRole('agent')
               ->select(['id', 'regid', 'platform'])
               ->first();

          $customer = User::where('id', $userId)
               ->where('role', 'customer')
               ->first();

          if ($agent && $agent->platform !== 'web') {
               FCM::post($agent->regid, $agent->platform)
                    ->dispatch([
                         'type' => 'incoming_chat',
                         'title' => 'You have new message from customers',
                         'message' => "Let's check the message right now",
                         'customer' => [
                              'id' => $customer?->id ?: null,
                              'name' => $customer?->name ?: 'Guest',
                              'profile' => $customer?->profile ?: config('services.placeholder_avatar'),
                         ],
                         'data' => $resultMessage
                    ]);
          }

          SocketBroadcast::channel('refresh_agent')
               ->destination([])
               ->dispatch([
                    'company_id' => $companyId,
                    'agent_id' => $agent->id,
                    'customer_id' => $customer?->id,
               ]);
     }
}