<?php

namespace App\Actions\Account;

use App\Helpers\JwtToken;
use App\Mail\DeletedAccount;
use App\Models\Data\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DeleteAccount
{
     public function handle($userId)
     {
          $user = User::findOrFail($userId);
          $user->update([
               'regid' => null,
               'platform' => null,
               'device_id' => null,
               'request_delete_send_at' => null,
               'deleted_at' => now(),
               'request_delete_at' => now(),
          ]);
          JwtToken::blacklist();

          // send email delete account
          try {
               Mail::to($user->email)->send(new DeletedAccount($user->name));
          } catch (\Exception $e) {
               logger('ERROR SEND EMAIL DELETE ACCOUNT ', [$e->getMessage()]);
          }
     }
}
