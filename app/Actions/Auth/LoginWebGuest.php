<?php

namespace App\Actions\Auth;

use App\Helpers\Crypto;
use App\Helpers\JwtToken;
use App\Helpers\Yellow;
use App\Mail\NewAccountGuest;
use App\Models\Data\CustomerDetail;
use App\Models\Data\User;
use App\Models\Util\Setting;
use App\Services\User\UserService;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LoginWebGuest
{
     public function __construct(
          private $userService = new UserService
     ) {
     }
     public function handle(Request $request)
     {
          $email = $request->email;
          $phone_code = $request->phone_code;
          $regid = base64_encode(date('YmdHis').$email);
          $phone = Yellow::phoneNumber($request->phone, $phone_code);
          if (!$user = $this->userService->findCustomerByEmailOrPhoneWithTrashed($email, $phone)) {
               $password = Yellow::randomPassword();

               $user = User::create([
                    'role' => 'customer',
                    'name' => $request->name,
                    'email' => $request->email,
                    'profile' => config('services.placeholder_avatar'),
                    'password' => $password,
                    'phone' => $phone,
                    'phone_code' => $phone_code,
                    'email_verified_at' => now(),
                    'username' => Yellow::createUsername($request->name),
                    'platform' => 'web',
                    'regid' => $regid,
               ]);

               CustomerDetail::create([
                    'user_id' => $user->id,
                    'personal_name' => $request->name,
                    'personal_email' => $request->email,
                    'personal_phone' => $request->phone,
                    'personal_phone_code' => $phone_code,
               ]);

               $this->sendEmailRegister($request, $user, $password);
          } else {
               $user->update([
                    'platform' => 'web',
                    'regid' => $regid,
                    'deleted_at' => null,
                    'request_delete_at' => null
               ]);
          }

          return [
               'id' => $user->id,
               'name' => $user->name,
               'profile' => asset($user->profile),
               'token' => $regid
          ];
     }


     private function sendEmailRegister(Request $request, $user, $password)
     {
          $linkPsApps = Setting::keys(['customer_play_store', 'customer_apps_store']);
          Mail::to($request->email)->send(new NewAccountGuest([
               'customer_name' => $user->name,
               'password' => $password,
               'company_name' => $request->company_name,
               'email' => $user->email,
               'playstore_link' => @$linkPsApps['customer_play_store'],
               'appstore_link' => @$linkPsApps['customer_apps_store'],
          ]));
     }
}
