<?php

namespace App\Actions\Profile;

use App\Helpers\BadRequestException;
use App\Helpers\Nexmo;
use App\Models\Data\User;
use App\Models\Util\OtpToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class EditPhoneNumber
{
     public function sendOtp(Request $request, $userId)
     {
          $phone = $request->phone;
          $otp = rand(1111, 9999);
          $token = Hash::make($otp);
          logger($otp);


          OtpToken::create([
               'email' => $userId,
               'token' => $token,
               'source' => 'edit-phone-customer',
               'available_until' => now()->addMinutes(2),
               'parent' => 'users',
          ]);

          Nexmo::from("Kontakami")->to($phone)->send("Kontakami - {$otp} is OTP verification code for your account.");

          Cache::put("edit-phone-customer-{$token}", [
               'phone' => $phone,
               'phone_code' => $request->phone_code
          ], now()->addMinutes(2));

          return base64_encode($token);
     }

     public function handle(Request $request, $userId)
     {
          $token = base64_decode($request->otp_token);
          if (!$otpToken = OtpToken::where('source', 'edit-phone-customer')->activeToken($userId, $token)->first()) {
               throw new BadRequestException('Your OTP is incorrect. Please try again');
          }

          if (!Hash::check($request->otp, $otpToken->token)) {
               throw new BadRequestException("Your OTP is incorrect. Please try again");
          }
          if (!$tokenCacheData = Cache::get("edit-phone-customer-{$token}")) {
               throw new BadRequestException("Your OTP is incorrect. Please try again");
          }

          $otpToken->delete();
          User::withTrashed()->where('phone',$tokenCacheData['phone'])->whereNotNull('deleted_at')->delete();
          return User::where('id', $userId)
               ->update([
                    'phone' => $tokenCacheData['phone'],
                    'phone_code' => $tokenCacheData['phone_code']
               ]);
     }
}
