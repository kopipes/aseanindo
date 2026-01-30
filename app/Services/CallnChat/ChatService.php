<?php

namespace App\Services\CallnChat;

use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\Chat\ChatUser;
use App\Models\Data\User;
use Illuminate\Support\Str;

class ChatService
{
     public function __construct(
          public $chat = Chat::class,
          public $message = ChatMessage::class,
          public $chatUser = ChatUser::class
     ) {
     }


     public function findAllChatHistoryCustomer($customerId, $limit = 10)
     {
          return $this->chat::with(['agent'])
               ->join('companies', 'companies.id', 'chats.company_id')
               ->byCustomerId($customerId)
               ->where('chats.is_customer', 1)
               ->select([
                    'chats.id', 'chats.company_id', 'companies.name as company_name', 'chats.last_chat as last_message',
                    'chats.end_at', 'chats.start_at', 'chats.status'
               ])
               ->latest('chats.end_at')
               ->paginate($limit ?: 10);
     }
     public function findActiveChatAgent($agentId,$customerId = null)
     {
          return $this->chatUser::join('chats', 'chats.id', 'chat_users.chat_id')
               ->join('chat_users as customer',function($join){
                    $join->on('customer.chat_id','chats.id');
                    $join->whereIn('customer.role',['guest','customer']);
               })
               ->where('chat_users.user_id', $agentId)
               ->where('customer.user_id', '!=',$customerId)
               ->where('chat_users.role', 'agent')
               ->where('chats.status', 'active')
               ->select(['chats.id'])
               ->first();
     }

     public function findActiveChatByAgentId($agentId)
     {
          return $this->chatUser::join('chats', 'chats.id', 'chat_users.chat_id')
               ->join('chat_users as customer',function($join){
                    $join->on('customer.chat_id','chats.id');
                    $join->whereIn('customer.role',['guest','customer']);
               })
               ->where('chat_users.user_id', $agentId)
               ->where('chat_users.role', 'agent')
               ->where('chats.status', 'active')
               ->select(['chats.id'])
               ->first();
     }

     public function findActiveChatAgentWithCustomer($agentId,$customerId = null)
     {
          return $this->chatUser::join('chats', 'chats.id', 'chat_users.chat_id')
               ->join('chat_users as customer',function($join){
                    $join->on('customer.chat_id','chats.id');
                    $join->whereIn('customer.role',['guest','customer']);
               })
               ->where('chat_users.user_id', $agentId)
               ->when(!$customerId,fn($query)=>$query->whereNull('customer.user_id'))
               ->when($customerId,fn($query)=>$query->where('customer.user_id',$customerId))
               ->where('chat_users.role', 'agent')
               ->where('chats.status', 'active')
               ->select(['chats.id'])
               ->first();
     }

     public function findActiveChatIdCustomer($customerId)
     {
          return $this->chatUser::join('chats', 'chats.id', 'chat_users.chat_id')
               ->where('user_id', $customerId)
               ->where('role', 'customer')
               ->where('chats.status', 'active')
               ->select(['chats.id'])
               ->first();
     }

     public function getAllConversation($companyId, $chatId)
     {
          return $this->chat::with([
               'message:chat_messages.chat_id,chat_messages.user_id,message_type as type,message as content,chat_messages.created_at as time,company_users.name,company_users.profile,chat_messages.created_at as date_time'
          ])
               ->where('company_id', $companyId)
               ->where('id', $chatId)
               ->select(['id', 'status'])
               ->first();
     }
     public function findChatById($chatId)
     {
          return $this->chat::find($chatId);
     }

     public function findCompanyIdChat($chatId)
     {
          return $this->chat::select('company_id')
               ->where('id', $chatId)
               ->firstOrFail();
     }

     
     public function findAllActiveChatUser($chatId)
     {
          return $this->chatUser::query()
               ->where('chat_id', $chatId)
               ->whereNull('end_at')
               ->select(['company_id', 'user_id', 'role', 'id'])
               ->get();
     }
     
     public function findActiveChatUserByRole($userId,$role = 'customer')
     {
          return $this->chat::with(['agent','customer'])
          ->join('chat_users',function($join) use($userId,$role){
               $join->on('chats.id','chat_users.chat_id');
               $join->where('chat_users.user_id',$userId);
               $join->where('chat_users.role',$role);
          })
          ->where('chats.is_customer',true)
          ->where('chats.status','active')
          ->whereNull('chats.end_at')
          ->latest('chats.created_at')
          ->select([
               'chats.id','chats.company_id'
          ])
          ->first();
     }
     
     public function findOrCreateNewChat($chatId, $companyId, $destination, $userId, $guestId = null)
     {
          if (!$chat = $this->findChatById($chatId)) {
               $category = 'Customer to Agent'; // 'Customer to Agent','Agent to Customer','Agent to Agent','Agent to SPV','Team'
               $user = User::find($userId ?: $guestId);
               $role = 'guest';
               if ($user) {
                    if ($user->role === 'agent') {
                         $category = 'Agent to Customer';
                    }
                    $role = $user->role;
               }
               $chat = $this->chat::create([
                    'channel' => 'Inbound',
                    'category' => $category,
                    'company_id' => $companyId,
                    'start_at' => now(),
                    'last_chat_by' => $user ? $user->name : 'Guest',
                    'last_chat_role' => $role,
                    'is_customer' => 1,
                    'status' => 'active',
                    'guest_customer_id' => $guestId
               ]);

               ChatUser::insert([
                    [
                         'id' => Str::uuid(),
                         'created_at' => now(),
                         'company_id' => $companyId,
                         'chat_id' => $chat->id,
                         'user_id' => $userId ?: $guestId,
                         'role' => $role,
                         'start_at' => now(),
                         'unread_message' => 0
                    ],
                    [
                         'id' => Str::uuid(),
                         'created_at' => now(),
                         'company_id' => $companyId,
                         'chat_id' => $chat->id,
                         'user_id' => $destination,
                         'role' => 'agent',
                         'start_at' => now(),
                         'unread_message' => 0
                    ]
               ]);
          }
          return $chat;
     }
}
