<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\BroadcastNotification;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Api\Auth\LoginWithTokenResource;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @group Authentication
 * @sorting 1
 */
class ApiLoginController extends ApiController
{
    public function __construct(
        private UserService $userService
    ) {
    }
    /**
     * Login
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam email string required email regex <pre style="font-size:10px">/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/</pre>
     * @bodyParam password string required regex <pre style="font-size:10px">/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/</pre>
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "The email you entered is incorrect",
     *   "validate": {
     *       "email": [
     *       "The email you entered is incorrect"
     *       ]
     *   }
     * }
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "code": "99d8dcc8-a764-4c16-9f2a-ff644a0df9ee",
     *       "name": "John Doe",
     *       "email": "rino@gamin.com",
     *       "profile": "https://yelow-app-storage.s3.ap-southeast-1.amazonaws.com/cnako525c6GTGkUq1nefIJ38mXinpV5JovDMuuws.png",
     *       "phone": "62857373773373",
     *       "role": "customer",
     *       "lang": "en",
     *       "username": "oke_oce",
     *       "request_delete_account_at": null,
     *       "token": {
     *       "token": "new-token-value-replace-current-token-with-this",
     *       "expiredAt": 1693449772
     *       }
     *   }
     *}
     */
    public function __invoke(Request $request)
    {
        $this->validates([
            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'password' => 'required|min:8|max:50|regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/',
        ]);

        if (!$user = $this->userService->findCustomerByEmail($request->email)) {
            return $this->validateException(['email' => ['The email you entered is incorrect']]);
        }
        if (!Hash::check($request->password, $user->password)) {
            return $this->validateException(['password' => ['The password you entered is incorrect']]);
        }
        BroadcastNotification::to($user)
            ->channel('force_logout')
            ->dispatch([
                'type' => 'user-login',
                'title' => 'Authentication',
                'message' => "You've signed in on a different device",
            ]);
        $user->update([
            'regid' => $request->header('regid'),
            'platform' => $request->header('platform'),
            'device_id' => $request->header('device-id')
        ]);
        return $this->sendSuccess(new LoginWithTokenResource($user));
    }
}
