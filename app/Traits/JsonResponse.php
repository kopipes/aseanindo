<?php
namespace App\Traits;

use App\Enum\ErrorApi;
use App\Helpers\JwtToken;
use Illuminate\Support\Facades\Validator;

trait JsonResponse
{
    public function sendSuccess($data, $message = "success")
    {
        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function sendMessage($message)
    {
        return response()->json([
            'status' => 200,
            'message' => $message,
        ]);
    }

    public function unauthorized($message, $err = ErrorApi::UNAUTHORIZED)
    {
        return response()->json([
            'status' => 401,
            'error' => $err,
            'message' => $message,
        ], 401);
    }

    public function badRequest($message, $err = ErrorApi::BAD_REQUEST)
    {
        return response()->json([
            'status' => 400,
            'error' => $err,
            'message' => $message,
        ], 400);
    }

    public function forbidden($message, $err = ErrorApi::FORBIDDEN)
    {
        return response()->json([
            'status' => 403,
            'error' => $err,
            'message' => $message,
        ], 403);
    }

    public function auth(){
        try{
            $decodedToken = JwtToken::decode();
            return $decodedToken->data;
        }catch(\Exception $e){}
        return null;
    }

    public function validates($rules, $message = [], $attributes = [])
    {
        $validator = Validator::make(request()->all(), $rules, $message, $attributes);
        if ($validator->fails()) {
            response()->json([
                'status' => 400,
                'error' => 'bad_request',
                'message' => $validator->errors()->first(),
                'validate' => $validator->errors()
            ], 400)->send();
            exit();
        }
    }

    public function validateException($message = [])
    {
        return response()->json([
            'status' => 400,
            'error' => 'bad_request',
            'message' => @collect($message)->first()[0],
            'validate' => $message
        ], 400);
    }
}