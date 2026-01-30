<?php
namespace App\Traits;


use Illuminate\Contracts\Validation\Validator;

trait FailedValidation
{
     public function failedValidation(Validator $validator)
     {
          response()->json([
               'status' => 400,
               'error' => 'bad_request',
               'message' => $validator->errors()->first(),
               'validate' => $validator->errors()
          ], 422)->send();
          exit();
     }
}