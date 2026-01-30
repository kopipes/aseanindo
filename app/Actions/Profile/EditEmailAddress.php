<?php

namespace App\Actions\Profile;

use App\Helpers\BadRequestException;
use App\Mail\VerificationOtp;
use App\Models\Data\User;
use App\Models\Util\OtpToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class EditEmailAddress
{
     public function sendOtp(Request $request, $userId)
     {
          $email = $request->email;
          $otp = rand(1111, 9999);
          $token = Hash::make($otp);
          logger($otp);


          OtpToken::create([
               'email' => $userId,
               'token' => $token,
               'source' => 'edit-email-customer',
               'available_until' => now()->addMinutes(2),
               'parent' => 'users',
          ]);

          Mail::to($email)->send(new VerificationOtp($otp, 'edit-profile'));

          Cache::put("edit-email-customer-{$token}", [
               'email' => $email,
          ], now()->addMinutes(2));

          return base64_encode($token);
     }

     public function handle(Request $request, $userId)
     {
          $token = base64_decode($request->otp_token);
          if (!$otpToken = OtpToken::where('source', 'edit-email-customer')->activeToken($userId, $token)->first()) {
               throw new BadRequestException('Your OTP is incorrect. Please try again');
          }

          if (!Hash::check($request->otp, $otpToken->token)) {
               throw new BadRequestException("Your OTP is incorrect. Please try again");
          }
          if (!$tokenCacheData = Cache::get("edit-email-customer-{$token}")) {
               throw new BadRequestException("Your OTP is incorrect. Please try again");
          }

          $otpToken->delete();
          User::withTrashed()->where('email',$tokenCacheData['email'])->whereNotNull('deleted_at')->delete();
          return User::where('id', $userId)
               ->update([
                    'email' => $tokenCacheData['email'],
               ]);
     }
}
