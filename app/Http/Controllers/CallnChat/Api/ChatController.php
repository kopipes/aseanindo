<?php

namespace App\Http\Controllers\CallnChat\Api;

use App\Actions\CallnChat\EndChatSession;
use App\Services\CallnChat\ChatService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\CallnChat\SendMessage;
use App\Helpers\BadRequestException;
use App\Http\Requests\CallnChat\Chat\SendMessageRequest;
use App\Services\Ticket\TicketService;

class ChatController extends Controller
{
    public function __construct(
        private ChatService $chatService
    ) {
    }
    public function index(Request $request, $id)
    {
        return $this->chatService->getAllConversation($request->companyId, $id);
    }
    public function store(SendMessageRequest $request, SendMessage $sendMessage, $category)
    {
        /**
         * $category = file , location , message
         * 
         * $messageFile = [
         *      "url" => "https:://",
         *      "name" => "name.pdf",
         *      "format => "pdf,
         *      "size" => "5,3 MB"
         * ]
         * 
         * $messageLocation = [
         *      "address" => "Semarang",
         *      "lat" : "-9.039449",
         *      "lng" : "4,039393"
         * ]
         */
        try {
            $companyId = $request->companyId;
            // $userId = $request->user_id;
            $userId = null;
            $result = $sendMessage->handle($request, $userId, $category, $companyId);
            return response()->json($result);
        } catch (BadRequestException $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function destroy(Request $request, EndChatSession $endChatSession, $chatId, $agentId)
    {
        $userId = $request->header('user-account') ?: null;
        $endChatSession->handle($chatId, $agentId, $userId, $userId ? 'customer' : 'guest');
        return response()->json('success');
    }

    public function csatTemplate(Request $request,TicketService $ticketService,$ticketId){
        $result = $ticketService->findCsatTemplateByTicketId($ticketId);
        return response()->json($result);
    }
}
