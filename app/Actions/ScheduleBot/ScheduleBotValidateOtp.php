<?php
namespace App\Actions\ScheduleBot;

use Illuminate\Http\Request;
use App\Models\Util\OtpToken;
use App\Helpers\BadRequestException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class ScheduleBotValidateOtp
{
     public function handle(Request $request, $companyId)
     {
          $otpId = $request->verification_id;
          $email = $request->email;
          $session = $request->session;
          $otp = $request->otp;
          $productId = $request->product_id;

          $otpToken = OtpToken::query()
               ->where('id', $otpId)
               ->where('email', $email)
               ->where('source', 'booking-schedule-bot')
               ->first();
          if (!$otpToken) {
               throw new BadRequestException("Your OTP is invalid.Please try again");
          }
          $expiredAt = date('Y-m-d H:i:s', strtotime($otpToken->available_until));
          $currentTime = date('Y-m-d H:i:s');
          if ($expiredAt < $currentTime) {
               throw new BadRequestException("Your OTP is expired.Please try again");
          }

          $token = $otp . $productId . $companyId . $session;
          if (!Hash::check($token, $otpToken->token)) {
               throw new BadRequestException("Your OTP is incorrect. Please try again");
          }


          // Add cache flag 1 minute only that this product for that email and company id is valid
          Cache::put("verify-booking-bot-product-" . $productId . $companyId . $session, true, now()->addMinutes(1));

     }
}