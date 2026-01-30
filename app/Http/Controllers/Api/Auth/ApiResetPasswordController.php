<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\ForgotPasswordCustomer;
use App\Actions\OTP\SendForgotPasswordOtp;
use App\Helpers\BadRequestException;
use App\Http\Controllers\ApiController;
use App\Services\User\UserService;
use Illuminate\Http\Request;

/**
 * @group Authentication/Reset Password
 * @sorting 4
 */
class ApiResetPasswordController extends ApiController
{
    public function __construct(
        private UserService $userService
    ) {
    }
    /**
     * Reset : Send Resend OTP
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam email string required
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "Email is not registered",
     *   "validate": {
     *       "email": [
     *       "Email is not registered"
     *       ]
     *   }
     * }
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "otp_token": "JDJ5JDEwJEkuZmxxZnV5Z09uN2k5WWh2N0JTQy5jOUJqbTcvM08yY3dYTjlpSTFiWFRJbjlKQWQ0WUF5"
     *   }
     * }
     */
    public function sendOtp(Request $request, SendForgotPasswordOtp $sendForgotPasswordOtp)
    {
        $this->validates(['email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/']);
        if (!$user = $this->userService->findCustomerByEmail($request->email, ['email'])) {
            return $this->validateException(['email' => ['Email is not registered']]);
        }
        $token = $sendForgotPasswordOtp->handle($user->email);

        return $this->sendSuccess(['otp_token' => $token]);
    }

    /**
     * Reset : Validate OTP
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam email string required
     * @bodyParam otp_token string required
     * @bodyParam otp integer required ex : 9999
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "Your OTP is incorrect. Please try again"
     * }
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "otp_id": "9a014e5c-20d5-4229-91ec-89e2b0f3b22b"
     *   }
     * }
     */
    public function validateOtp(Request $request, ForgotPasswordCustomer $forgotPasswordCustomer)
    {
        try {
            $this->validates([
                'otp_token' => 'required',
                'otp' => 'required',
                'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ]);

            $otpId = $forgotPasswordCustomer->validateOtp($request);

            return $this->sendSuccess(['otp_id' => $otpId]);
        } catch (BadRequestException $e) {
            return $this->badRequest($e->getMessage());
        }
    }

    /**
     * Reset Password
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam otp_id string required
     * @bodyParam password string required
     * @bodyParam password_confirmed string required
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "The password must be at least 8 characters.",
     *   "validate": {
     *       "password": [
     *       "The password must be at least 8 characters.",
     *       ],
     *       "password_confirmed": [
     *       "The password confirmed and password must match.",
     *       ]
     *   }
     * }
     * 
     * @response {
     *   "status": 200,
     *   "message": "Your password was changed successfully."
     * }
     */
    public function store(Request $request, ForgotPasswordCustomer $forgotPasswordCustomer)
    {
        try {
            $this->validates([
                'otp_id' => 'required',
                'password' => 'required|min:8|max:50|regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/',
                'password_confirmed' => 'required|same:password|min:8|max:50|regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/'
            ]);

            $forgotPasswordCustomer->handle($request);

            return $this->sendMessage('Your password was changed successfully.');
        } catch (BadRequestException $e) {
            return $this->badRequest($e->getMessage());
        }
    }
}
