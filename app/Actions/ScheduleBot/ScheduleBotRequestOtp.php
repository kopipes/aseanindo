<?php
namespace App\Actions\ScheduleBot;

use App\Models\Util\OtpToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Chat\ChatBotBookingVerification;


class ScheduleBotRequestOtp
{
     public function handle(Request $request, $companyId)
     {
          $email = $request->email;
          $productId = $request->product_id;
          $session = $request->session;

          /**
           * SEND OTP TO EMAIL
           */
          $otp = rand(1111, 9999);
          $token = Hash::make($otp . $productId . $companyId.$session);
          logger($otp);
          try {
               Mail::to($email)->send(new ChatBotBookingVerification($otp));
          } catch (\Exception $e) {
               logger($e);
          }

          $otpToken = OtpToken::updateOrCreate([
               'source' => 'booking-schedule-bot',
               'email' => $email,
          ], [
               'available_until' => now()->addMinutes(2),
               'token' => $token
          ]);

          return $otpToken->id;
     }
}