<?php

namespace App\Actions\Auth;

use App\Helpers\BadRequestException;
use App\Models\Data\User;
use App\Models\Util\OtpToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordCustomer
{
     public function validateOtp(Request $request)
     {
          $token = base64_decode($request->otp_token);
          if (!$otpToken = OtpToken::where('source', 'forgot-password-customer')->activeToken($request->email, $token)->first()) {
               throw new BadRequestException('Your OTP is incorrect. Please try again');
          }

          if (!Hash::check($request->otp, $otpToken->token)) {
               throw new BadRequestException("Your OTP is incorrect. Please try again");
          }
          return $otpToken->id;
     }

     public function handle(Request $request)
     {
          if (!$otp = OtpToken::where('source', 'forgot-password-customer')->where('id', $request->otp_id)->first()) {
               throw new BadRequestException('Your OTP is incorrect. Please try again');
          }

          return User::query()
               ->where('email', $otp->email)
               ->where('role', 'customer')
               ->update([
                    'password' => Hash::make($request->password)
               ]);
     }
}
