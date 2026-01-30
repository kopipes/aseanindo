<?php

namespace App\Actions\Profile;

use App\Helpers\BadRequestException;
use App\Models\Data\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EditPasswordUser
{
     public function handle(Request $request, $userId)
     {
          $user = User::where('id', $userId)->firstOrFail();

          if (!Hash::check($request->current_password, $user->password)) {
               throw new BadRequestException('The current password you entered is incorrect.');
          }

          $user->update([
               'password' => Hash::make($request->password)
          ]);
     }
}
