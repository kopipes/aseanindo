<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\RegisterCustomer;
use App\Actions\OTP\SendRegisterOtp;
use App\Helpers\BadRequestException;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @group Authentication/Register
 */
class ApiRegisterController extends ApiController
{

    /**
     * Validate Data
     * @sorting 2
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam name string required
     * @bodyParam email string required
     * @bodyParam password string required
     * @bodyParam password_confirmed string required
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "The password format is invalid.",
     *   "validate": {
     *       "password": [
     *       "The password format is invalid."
     *       ],
     *       "password_confirmed": [
     *       "Password confirmation does not match",
     *       "The password confirmed format is invalid."
     *       ]
     *   }
     * }
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "register_token": "9a977a379de00699893147b2fea1d3de"
     *   }
     * }
     */
    public function validateData(Request $request, RegisterCustomer $registerCustomer)
    {
        $this->validates([
            'name' => 'required',
            'email' => [
                'required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                Rule::unique('users', 'email')->where(function ($query) use ($request) {
                    return $query->where('role', 'customer')->whereNull('deleted_at');
                })
            ],
            'password' => 'required|min:8|max:50|regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/',
            'password_confirmed' => 'required|same:password|min:8|max:50|regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/',
        ], [
            'email.email' => 'The email you entered is incorrect',
            'email.unique' => 'This email account already exists',
            'password_confirmed.same' => 'Password confirmation does not match'
        ]);

        $token = $registerCustomer->validateData($request);

        return $this->sendSuccess(['register_token' => $token]);
    }

    /**
     * Validate Phone Number
     * @sorting 4
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam register_token string required
     * @bodyParam phone integer required ex: 62857474833757
     * @bodyParam phone_code integer required ex: 62
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "Phone number is already registered",
     *   "validate": {
     *       "phone": [
     *          "Phone number is already registered"
     *       ]
     *   }
     * }
     * 
     * @response {
     *   "status": 200,
     *   "message": {
     *       "register_token": "9a804e0ee22a1cdecacc9760c1a6f070"
     *   }
     * }
     */
    public function validatePhone(Request $request, RegisterCustomer $registerCustomer)
    {
        try {
            $this->validates([
                'phone' => [
                    'required', 'numeric', Rule::unique('users', 'phone')->where(function ($query) use ($request) {
                        return $query->where('role', 'customer')->whereNull('deleted_at');
                    })
                ],
            ], [
                'phone.unique' => 'Phone number is already registered',
            ]);
            $data = $registerCustomer->registerData($request);

            $token = $registerCustomer->updateData($request, $data, $request->only(['phone', 'phone_code']));
            return $this->sendMessage(['register_token' => $token]);

        } catch (BadRequestException $e) {
            return $this->badRequest($e->getMessage());
        }
    }


    /**
     * Reg : Send Resend OTP
     * @sorting 5
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam register_token string required
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "Your registration token not found"
     * }
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "otp_token": "JDJ5JDEwJC9kOHdIRTBpT0RUUmh4RDdFMVFEVy4ya0pxTW1RLlo2dFd1UEFtaHFQcUlqa3p1emQzRWN5"
     *   }
     * }
     */
    public function sendOtp(Request $request, RegisterCustomer $registerCustomer, SendRegisterOtp $sendRegisterOtp)
    {
        try {
            $this->validates(['register_token' => 'required']);

            $data = $registerCustomer->registerData($request);

            $token = $sendRegisterOtp->handle($data['phone']);

            return $this->sendSuccess(['otp_token' => $token]);
        } catch (BadRequestException $e) {
            return $this->badRequest($e->getMessage());
        }
    }

    /**
     * Reg : Validate OTP
     * @sorting 6
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam register_token string required
     * @bodyParam otp_token string required
     * @bodyParam otp integer required ex: 8473
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
     *       "otp_id": "9a00f80b-33b4-4987-927d-511672d0d4e6"
     *   }
     * }
     */
    public function validateOtp(Request $request, RegisterCustomer $registerCustomer)
    {
        try {
            $this->validates([
                'otp_token' => 'required',
                'register_token' => 'required',
                'otp' => 'required',
            ]);

            $data = $registerCustomer->registerData($request);
            $otpId = $registerCustomer->validateOtp($request, $data);

            return $this->sendSuccess(['otp_id' => $otpId]);
        } catch (BadRequestException $e) {
            return $this->badRequest($e->getMessage());
        }
    }

    /**
     * Submit Register
     * @sorting 7
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam register_token string required
     * @bodyParam otp_id string required
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "Your OTP is incorrect. Please try again"
     * }
     * 
     * @response {
     *   "status": 200,
     *   "message": "Your account was successfully confirmed. You are now signed in."
     * }
     */
    public function store(Request $request, RegisterCustomer $registerCustomer)
    {
        try {
            $this->validates([
                'otp_id' => 'required',
                'register_token' => 'required',
            ]);
            $registerCustomer->handle($request);

            return $this->sendMessage('Your account was successfully confirmed. You are now signed in.');
        } catch (BadRequestException $e) {
            return $this->badRequest($e->getMessage());
        }
    }
}
