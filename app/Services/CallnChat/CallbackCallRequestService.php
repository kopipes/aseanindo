<?php

namespace App\Services\CallnChat;

use App\Models\Call\CallbackCallRequest;

class CallbackCallRequestService
{
     public function __construct(
          public $model = CallbackCallRequest::class
     ) {
     }

     public function deleteCustomerCallbackRequest($customerId, $companyId)
     {
          return $this->model::where('company_id', $companyId)
               ->where('customer_id', $customerId)
               ->delete();
     }
}
