<?php

namespace App\Actions\Profile;

use App\Services\User\CustomerDetailService;
use Illuminate\Http\Request;

class EditBioUser
{
     public function __construct(
          private $customerDetailService = new CustomerDetailService
     ) {
     }
     public function handle(Request $request, $userId)
     {
          $this->customerDetailService->updateOrCreate($userId, [
               'bio' => $request->bio,
          ]);
     }
}
