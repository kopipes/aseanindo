<?php

namespace App\Actions\OTP;

use App\Mail\VerificationOtp;
use App\Models\Util\OtpToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SendForgotPasswordOtp
{
     public function handle($email)
     {
          $otp = rand(1111, 9999);
          logger($otp);
          $otpToken = OtpToken::create([
               'email' => $email,
               'token' => Hash::make($otp),
               'source' => 'forgot-password-customer',
               'available_until' => now()->addMinutes(2),
               'parent' => 'users',
          ]);
          Mail::to($email)->send(new VerificationOtp($otp, 'forgot-password'));
          return base64_encode($otpToken->token);
     }
}
