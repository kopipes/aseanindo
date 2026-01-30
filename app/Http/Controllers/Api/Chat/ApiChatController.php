<?php

namespace App\Http\Controllers\Api\Chat;

use App\Actions\CallnChat\EndChatSession;
use App\Actions\CallnChat\SendMessage;
use App\Helpers\BadRequestException;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Chat\SendMessageRequest;
use App\Http\Resources\Api\Chat\ChatHistoryItemResource;
use App\Services\CallnChat\ChatService;
use Illuminate\Http\Request;

/**
 * @group Chat
 */
class ApiChatController extends ApiController
{
    public function __construct(
        private ChatService $chatService
    ) {
    }

    /**
     * Chat History
     * 
     * @authenticated
     * @defaultParam
     * 
     * @queryParam limit integer optional default 10
     * @queryParam page integer optional default 1
     * 
     * @response {
     *       "status": 200,
     *       "message": "success",
     *       "data": [{
     *           "id": "9a017a10-2cc8-4e53-a561-07289aef1e65",
     *           "status": "end",
     *           "company_id": "99c02fd2-7094-4064-a85f-e73bdf1cb50b",
     *           "company_name": "PT Taman Media Indonesia",
     *           "last_message": "jnmm",
     *           "date": "2023-08-29 19:35:18",
     *           "agent_id": "99c87ee3-b301-4500-ad84-369c37950520",
     *           "agent_profile": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/profile/NZ7k9hHOMVZjwGxlvtexeQHbbDHY9V69dZJTEeg0.png",
     *           "agent_name": "Bobby Moen DDS",
     *           "helpdesk_category": "Android Engginer, Marketing"
     *       }]
     *   }
     */
    public function index(Request $request)
    {
        $userId = $this->user()?->id;
        $items = $this->chatService->findAllChatHistoryCustomer($userId, $request->get('limit', 10));
        return $this->sendSuccess(ChatHistoryItemResource::collection($items));
    }

    /**
     * Active Chat
     * 
     * @authenticated
     * @defaultParam
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "chat_id": "9a0f1b33-28f0-4ec4-8740-83b585916e52",
     *       "agent_id": "99c87ee3-b301-4500-ad84-369c37950520",
     *       "agent_name": "Bobby Moen DDS",
     *       "agent_image": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/profile/NZ7k9hHOMVZjwGxlvtexeQHbbDHY9V69dZJTEeg0.png"
     *   }
     * }
     */
    public function active(Request $request)
    {
        try {
            $user = $this->user();
            $chat = $this->chatService->findActiveChatUserByRole($user->id, $user->permission);
            $agent = $chat?->agent;
            if (!$chat || !$agent) {
                return $this->sendSuccess(null);
            }
            return $this->sendSuccess([
                'chat_id' => $agent->chat_id,
                'company_id' => $chat->company_id,
                'agent_id' => $agent->id,
                'agent_name' => $agent->name,
                'agent_image' => asset($agent->profile ?: config('services.placeholder_avatar')),
            ]);
        } catch (\Exception $e) {
            return $this->sendSuccess(null);
        }
    }

    /**
     * Conversation Message
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required chat id
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": [{
     *           "chat_id": "99f382a1-ac80-4644-840e-37a3b72ae7ad",
     *           "user_id": null,
     *           "type": "message",
     *           "content": "yee",
     *           "time": "14:04",
     *           "name": "Guest",
     *           "profile": "https://yelow-app-storage.s3.ap-southeast-1.amazonaws.com/cnako525c6GTGkUq1nefIJ38mXinpV5JovDMuuws.png"
     *       },
     *       {
     *           "chat_id": "99f382a1-ac80-4644-840e-37a3b72ae7ad",
     *           "user_id": "99c87ee3-abd7-4b52-9b4d-b42199f69ed5",
     *           "type": "file",
     *           "content": {
     *               "url": "http://localhost:8000/uploads/image/tOIF3qlrbWpxDv4QeohC6KbiRsoZYz7sPpQFqThq.jpg",
     *               "name": "avatar-8.jpg",
     *               "format": "jpg",
     *               "size": "11.46 KB"
     *           },
     *           "time": "14:20",
     *           "name": "Dr. Haley Reichert",
     *           "profile": "http://localhost:8000/uploads/profile/KnBTjqx79w22GLBV0JjKxDrdxZtTRl7reZjBvLLS.jpg"
     *       },
     *       {
     *           "chat_id": "99f382a1-ac80-4644-840e-37a3b72ae7ad",
     *           "user_id": null,
     *           "type": "location",
     *           "content": {
     *               "address": "Jalan Bina Remaja, RW 18, Srondol Wetan, Banyumanik, Semarang, Central Java, Java, 50259, Indonesia",
     *               "lat": "-7.064273423870867",
     *               "lng": "110.41668982862839"
     *           },
     *           "time": "10:19",
     *           "name": "Guest",
     *           "profile": "https://yelow-app-storage.s3.ap-southeast-1.amazonaws.com/cnako525c6GTGkUq1nefIJ38mXinpV5JovDMuuws.png"
     *       }
     *   ]
     *}
     */
    public function conversation(Request $request, $id)
    {
        $chat = $this->chatService->findCompanyIdChat($id);
        $items = $this->chatService->getAllConversation($chat->company_id, $id);
        return $this->sendSuccess($items?->message ?: []);
    }


    /**
     * Send Chat to Agent
     * 
     * <ul>
     *      <li>Hit api ini hanya ketika mengirim pesan pertama kali</li>
     *      <li>Ketika pengirim mengirimkan pesan, pengirim akan otomatis append chat yang di kirim ke list conversation nya</li>
     *      <li>Ketika mendapatkan respon api success, pengirim akan melakukan proses join broadcast socket dengan menggunakan chat id</li>
     *      <li>
     *          Kemudian setelah hit api dan mendapatkan response success akan melakukan emit event socket ke lawan bicara dengan type channel <i><strong>new-message</strong></i>
     *      </li>
     *      <li>
     *          Object Response Chat (Digunakan untuk emit socket ke lawan bicara juga)
     *          <code style="font-size:12px;margin-top:5px;white-space: break-spaces;">
     * Chat Type Message
     * {
     *      type: "message",
     *      content: "Halo kak ada yang bisa saya bantu",
     *      time: "17:19",
     *      user_id: userId,
     *      name: userName,
     *      profile: userImage,
     *      chat_id: chatId,
     * }
     * ===================================================
     * Chat Type Location
     * {
     *       type: "location",
     *       content: {
     *               address: "Jalan Bina Remaja, RW 18",
     *               lat: "-7.064273423870867",
     *               lng: "110.41668982862839",
     *       },
     *       time: "17:19",
     *       user_id: userId,
     *       name: userName,
     *       profile: userImage,
     *       chat_id: chatId,
     *  }
     * ===================================================
     * Chat Type File
     * {
     *       type: "file",
     *       content: {
     *               url: "http://localhost:8000/uploads/image/YlPBAkjs9WAYShFS6xigKifu7JhaZH1LOcvHKxJ8.jpg",
     *               name: "avatar-4.jpg",
     *               format: "jpg",
     *               size: "13.67 KB",
     *       },
     *       time: "17:19",
     *       user_id: userId,
     *       name: userName,
     *       profile: userImage,
     *       chat_id: chatId,
     * }
     *          </code>
     *      </li>
     * </ul>
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam chat_id string optional required if want to send to existing chat, empty if want to start new chat with agent
     * @bodyParam company_id string required
     * @bodyParam category string required in file, location, message
     * @bodyParam destination string required agent id
     * @bodyParam message string required or type file if category is file
     * @bodyParam message[address] string optional required if category location
     * @bodyParam message[lat] string optional required if category location
     * @bodyParam message[lng] string optional required if category location
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "The agent is not available now. Please try again later."
     * }
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "chat_id": "9a0f1b33-28f0-4ec4-8740-83b585916e52",
     *       "user_id": "9a0f1b33-28f0-4ec4-8740-83b585916e52",
     *   }
     * }
     */
    public function sendChatMessage(SendMessageRequest $request, SendMessage $sendMessage)
    {
        try {
            $userId = $this->user()?->id;
            $result = $sendMessage->handle($request, $userId, $request->category, $request->company_id);
            return $this->sendSuccess([
                ...$result ?: [],
                'user_id' => $userId
            ]);
        } catch (BadRequestException $e) {
            return $this->badRequest($e->getMessage());
        }
    }


    /**
     * End Chat
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required chat id
     * 
     * @response {
     *   "status": 200,
     *   "message": "Chat ended"
     * }
     */
    public function endChat(Request $request, EndChatSession $endChatSession, $chatId)
    {
        $user = $this->user();
        $chat = $this->chatService->findActiveChatUserByRole($user->id, $user->permission);
        if ($chat && $chat->id === $chatId) {
            $endChatSession->handle($chatId, $chat->agent->id, $user->id, $user->permission);
        }
        return $this->sendMessage('Chat ended');
    }


    /**
     * End Chat By Agent
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required chat id
     * 
     * @requestBody multipart/form-data
     * @bodyParam customer_id string required 
     * @bodyParam customer_role string required in customer or guest
     * 
     * @response {
     *   "status": 200,
     *   "message": "Chat ended"
     * }
     */

    public function endChatByAgent(Request $request, EndChatSession $endChatSession, $chatId)
    {
        $user = $this->user();
        if ($user->permission !== 'agent') {
            abort(401);
        }
        $this->validates([
            'customer_role' => 'required|in:customer,guest',
        ]);
        $chat = $this->chatService->findActiveChatUserByRole($user->id, $user->permission);
        if ($chat && $chat->id === $chatId) {
            $endChatSession->handle($chatId, $user->id, $request->customer_id, $request->customer_role, 'agent');
        }
        return $this->sendMessage('Chat ended');
    }
}