<?php

namespace App\Http\Controllers\Api\Profile;

use App\Actions\Profile\EditEmailAddress;
use App\Actions\Profile\EditPhoneNumber;
use App\Helpers\BadRequestException;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

/**
 * @group Profile/Edit With OTP
 */
class ApiEditProfileController extends ApiController
{
    /**
     * Edit Phone : Send Resend OTP
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam phone_code string required ex: 62
     * @bodyParam phone string required ex: 6285747488618
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "Phone number is already registered",
     *   "validate": {
     *       "phone": [
     *       "Phone number is already registered"
     *       ]
     *   }
     * }
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "otp_token": "JDJ5JDEwJHpqQy9hU1h4MUFLci9lQXlOc0lvTHV0SkRKMTVMRGRVUzZGYW55RUtuV2xnaDJnRjVEVC9X"
     *   }
     * }
     */
    public function sendOtpEditPhone(Request $request, EditPhoneNumber $editPhoneNumber)
    {
        try {
            $this->validates([
                'phone' => [
                    'required', 'numeric', Rule::unique('users', 'phone')->where(function ($query) use ($request) {
                        return $query->where('role', 'customer')->whereNull('deleted_at');
                    })
                ],
                'phone_code' => 'required'
            ], [
                'phone.unique' => 'Phone number is already registered',
            ]);

            $token = $editPhoneNumber->sendOtp(
                request : $request,
                userId: $this->user()?->id,
            );

            return $this->sendSuccess(['otp_token' => $token]);
        } catch (BadRequestException $e) {
            return $this->badRequest($e->getMessage());
        }
    }

    /**
     * Edit Phone : Validate OTP & Submit
     * 
     * @authenticated
     * @defaultParam
     * 
     * 
     * @requestBody multipart/form-data
     * @bodyParam otp_token string required ex: msdomiofidkfd938483kjdsk
     * @bodyParam otp string required ex: 1945
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "Your OTP is incorrect. Please try again"
     * }
     * 
     * @response {
     *   "status": 200,
     *   "message": "Your phone number was changed successfully."
     * }
     */
    public function updatePhoneNumber(Request $request,EditPhoneNumber $editPhoneNumber)
    {
        try {
            $this->validates([
                'otp_token' => 'required',
                'otp' => 'required',
            ]);

            $editPhoneNumber->handle(
                request : $request,
                userId: $this->user()?->id,
            );

            return $this->sendMessage('Your phone number was changed successfully.');

        } catch (BadRequestException $e) {
            return $this->badRequest($e->getMessage());
        }
    }

    /**
     * Edit Email : Send Resend OTP
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam email string required ex: mahfud@gmail.com
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "The email has already been taken.",
     *   "validate": {
     *       "email": [
     *       "The email has already been taken."
     *       ]
     *   }
     * }
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "otp_token": "JDJ5JDEwJDFMc0ZBNFZhQnRtb2lNeUxIZVFKWi4zVTdxaWVLSGpiTDFoY0NnUk44dTBBVHV2SkJMeXZX"
     *   }
     * }
     */
    public function sendOtpEditEmail(Request $request,EditEmailAddress $editEmailAddress)
    {
        $this->validates([
            'email' => [
                'required','email','regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                Rule::unique('users', 'email')->where(function ($query) use ($request) {
                    return $query->where('role', 'customer')->whereNull('deleted_at');
                })
            ],
        ]);
        
        $token = $editEmailAddress->sendOtp(
            request : $request,
            userId: $this->user()?->id,
        );

        return $this->sendSuccess(['otp_token' => $token]);
    }

    /**
     * Edit Email : Validate OTP & Submit
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam otp_token string required ex: msdomiofidkfd938483kjdsk
     * @bodyParam otp string required ex: 1945
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "Your OTP is incorrect. Please try again"
     * }
     * 
     * @response {
     *   "status": 200,
     *   "message": "Your email address was changed successfully."
     * }
     */
    public function updateEmailAddress(Request $request,EditEmailAddress $editEmailAddress)
    {
        try {
            $this->validates([
                'otp_token' => 'required',
                'otp' => 'required',
            ]);

            $editEmailAddress->handle(
                request : $request,
                userId: $this->user()?->id,
            );

            return $this->sendMessage('Your email address was changed successfully.');

        } catch (BadRequestException $e) {
            return $this->badRequest($e->getMessage());
        }
    }
}
