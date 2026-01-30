<?php

namespace App\Actions\OTP;

use App\Helpers\Nexmo;
use App\Models\Util\OtpToken;
use Illuminate\Support\Facades\Hash;

class SendRegisterOtp
{
     public function handle($phone)
     {
          $otp = rand(1111, 9999);
          logger($otp);
          $otpToken = OtpToken::create([
               'email' => $phone,
               'token' => Hash::make($otp),
               'source' => 'register-customer',
               'available_until' => now()->addMinutes(2),
               'parent' => 'users',
          ]);

          Nexmo::from("Kontakami")->to($phone)->send("Kontakami - {$otp} is OTP verification code for your account.");

          return base64_encode($otpToken->token);
     }
}
