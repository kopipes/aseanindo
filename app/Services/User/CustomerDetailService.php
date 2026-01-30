<?php

namespace App\Services\User;

use App\Models\Data\CustomerDetail;

class CustomerDetailService
{
     public function __construct(
          public $model = CustomerDetail::class
     ) {
     }

     public function updateOrCreate($userId, $properties)
     {
          return $this->model::updateOrCreate(
               ['user_id' => $userId],
               $properties
          );
     }
}
