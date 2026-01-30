<?php

namespace App\Actions\Auth;

use App\Helpers\BadRequestException;
use App\Helpers\Yellow;
use App\Models\Util\OtpToken;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class RegisterCustomer
{
     public function __construct(
          private $userService = new UserService
     ){}
     public function registerData(Request $request)
     {
          $token = $request->register_token;
          if (!$data =  Cache::get("register_customer_{$token}")) {
               throw new BadRequestException('Your registration token not found !');
          }
          return $data;
     }
     public function validateData(Request $request)
     {
          $token = md5(date('YmdHis') . rand(1111, 9999));
          Cache::put("register_customer_{$token}", $request->only([
               'name', 'email', 'password'
          ]), now()->addHours(6));

          return $token;
     }

     public function updateData(Request $request, $data, $properties)
     {
          $token = $request->register_token;
          Cache::put("register_customer_{$token}", [
               ...$data,
               ...$properties
          ], now()->addHours(6));

          return $token;
     }


     public function validateOtp(Request $request, $data)
     {
          $phone = $data['phone'];
          $token = base64_decode($request->otp_token);
          if (!$otpToken = OtpToken::where('source', 'register-customer')->activeToken($phone, $token)->first()) {
               throw new BadRequestException('Your OTP is incorrect. Please try again');
          }

          if (!Hash::check($request->otp, $otpToken->token)) {
               throw new BadRequestException("Your OTP is incorrect. Please try again");
          }

          $this->updateData($request, $data, [
               'otp_id' => $otpToken->id
          ]);
          return $otpToken->id;
     }


     public function handle(Request $request)
     {
          $data = $this->registerData($request);
          $otp_id = $request->otp_id;

          $name = @$data['name'];
          $email = @$data['email'];
          $phone = @$data['phone'];
          $password = @$data['password'];
          $phone_code = @$data['phone_code'];

          if (@$data['otp_id'] !== $otp_id) {
               throw new BadRequestException("Your OTP is incorrect. Please try again");
          }

          $user = $this->userService->findCustomerByEmailOrPhoneWithTrashed(
               email : $data['email'],
               phone : $data['phone']
          );

          return $this->userService->updateOrCreate($user?->id,[
               'email' => $email,
               'name' => $name,
               'username' => Yellow::createUsername($name),
               'password' => Hash::make($password),
               'phone' => $phone,
               'phone_code' => $phone_code,
               'role' => 'customer',
               'email_verified_at' => now(),
               'deleted_at' => null,
               'request_delete_at' => null
          ]);
     }
}
