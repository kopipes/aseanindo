<?php
namespace App\Actions\ChatBot;

use Illuminate\Http\Request;
use App\Helpers\BadRequestException;
use Illuminate\Support\Facades\Hash;
use App\Services\CallnChat\ChatBotService;

class ValidateOptBooking
{
     public function __construct(
          private $chatBotService = new ChatBotService
     ) {
     }
     public function handle(Request $request, $botId)
     {
          $chatBot = $this->chatBotService->findById($botId);
          $verificationToken = $chatBot->verification_token;
          $token = $verificationToken['token'];
          $expiredAt = date('Y-m-d H:i:s', strtotime($verificationToken['expired_at']));
          $currentTime = date('Y-m-d H:i:s');
          if ($expiredAt < $currentTime) {
               throw new BadRequestException("Your OTP is expired.Please try again");
          }
          if (!Hash::check($request->otp, $token)) {
               throw new BadRequestException("Your OTP is incorrect. Please try again");
          }

          /**
           * TODO : create ticket
           */
          return (new BookingTicketFromBot)->createTicket($chatBot);
     }
}