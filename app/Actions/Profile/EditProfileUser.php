<?php

namespace App\Actions\Profile;

use App\Helpers\Yellow;
use App\Models\Data\User;
use App\Services\User\CustomerDetailService;
use Illuminate\Http\Request;

class EditProfileUser
{
     public function __construct(
          private $customerDetailService = new CustomerDetailService
     ) {
     }
     public function handle(Request $request, $userId)
     {
          $user = User::findOrFail($userId);
          $userData = ['name' => $request->name ?: $user->name];
          if ($request->hasFile('profile')) $userData['profile'] = Yellow::uploadFile($request->file('profile'));

          $user->update($userData);
          $this->customerDetailService->updateOrCreate($userId, [
               'facebook' => $request->facebook,
               'twitter' => $request->twitter,
               'instagram' => $request->instagram,
               'hide_search' => $request->hide_profile ? true : false,
          ]);
     }
}
