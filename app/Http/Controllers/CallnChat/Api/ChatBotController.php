<?php

namespace App\Http\Controllers\CallnChat\Api;

use App\Helpers\Yellow;
use App\Services\Helpdesk\CompanyProductService;
use Illuminate\Http\Request;
use App\Actions\ChatBot\NextFlowBot;
use App\Http\Controllers\Controller;
use App\Actions\ChatBot\EndBotSession;
use App\Actions\ChatBot\SendBotMessage;
use App\Actions\ChatBot\StartBotSession;
use App\Services\CallnChat\ChatBotService;
use App\Actions\ChatBot\ResponseBotMessage;
use App\Actions\ChatBot\ValidateOptBooking;
use App\Actions\ChatBot\BookingTicketFromBot;
use App\Helpers\BadRequestException;
use Illuminate\Http\Response;

class ChatBotController extends Controller
{
    public function __construct(
        private ChatBotService $chatBotService
    ) {
    }

    public function index(Request $request, $botId)
    {
        return $this->chatBotService->findAllConversation($botId);
    }

    public function phoneCode(Request $request)
    {
        return Yellow::phoneCountryCode();
    }
    public function start(Request $request, StartBotSession $startBotSession)
    {
        $companyId = $request->companyId;
        $result = $startBotSession->handle($request, $companyId);
        return response()->json($result);
    }

    public function next(Request $request, NextFlowBot $nextFlowBot, $botId)
    {
        $result = $nextFlowBot->handle($botId);
        return response()->json($result);
    }

    public function sendMessage(Request $request, SendBotMessage $sendBotMessage, $botId)
    {
        $result = $sendBotMessage->handle($request, $botId);
        return response()->json($result);
    }

    public function responseMessage(Request $request, ResponseBotMessage $responseBotMessage, $botId)
    {
        $result = $responseBotMessage->handle($request, $botId);
        return response()->json($result);
    }

    public function product(Request $request, $botId)
    {
        return $this->chatBotService->findAllProductFlow($botId);
    }

    public function allProduct(Request $request,CompanyProductService $companyProductService,$category){
        $companyId = $request->companyId;
        $productId = [];
        if($product = $request->product){
            $productId = explode(',',$product);
        }
        return $companyProductService->findAllByCategory($companyId,$category,$productId);
    }

    public function booking(Request $request, BookingTicketFromBot $bookingTicketFromBot, $botId)
    {
        $bookingTicketFromBot->handle($request, $botId);
    }

    public function resendOtp(Request $request, BookingTicketFromBot $bookingTicketFromBot, $botId)
    {
        $chatBot = $this->chatBotService->findById($botId);
        $bookingTicketFromBot->sendOtp($chatBot, false);
        return response()->json('success');
    }

    public function validateOtp(Request $request, ValidateOptBooking $validateOptBooking, $botId)
    {
        try {
            $result = $validateOptBooking->handle($request, $botId);
            return response()->json($result);
        } catch (BadRequestException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function end(Request $request, EndBotSession $endBotSession, $botId)
    {
        $endBotSession->handle($botId);
        return response()->json('success');
    }
}
