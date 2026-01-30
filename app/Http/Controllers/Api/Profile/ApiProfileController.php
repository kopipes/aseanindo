<?php

namespace App\Http\Controllers\Api\Profile;

use App\Actions\Account\DeleteAccount;
use App\Actions\Profile\EditBioUser;
use App\Actions\Profile\EditPasswordUser;
use App\Actions\Profile\EditProfileUser;
use App\Helpers\BadRequestException;
use App\Helpers\JwtToken;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Api\Account\BlockedAccountItemResource;
use App\Models\Data\User;
use App\Models\Util\Setting;
use App\Services\User\UserService;
use App\Services\Util\BlockedAccountService;
use Illuminate\Http\Request;

/**
 * @group Profile
 */
class ApiProfileController extends ApiController
{
    public function __construct(
        private UserService $userService,
        private BlockedAccountService $blockedAccountService,
    ) {
    }
    /**
     * Detail Profile
     * 
     * @authenticated
     * @defaultParam
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "name": "mahfud",
     *       "email": "mahfud@mailinator.com",
     *       "phone": "629484884848",
     *       "profile": "https//blabla",
     *       "lang": "en",
     *       "created_at": "2023-08-28T23:41:10.000000Z",
     *       "bio": null,
     *       "facebook": null,
     *       "twitter": null,
     *       "instagram": null,
     *       "hide_search": null,
     *       "play_store": "https://play.google.com/store/apps/details?id=com.yelow.customer&hl=id",
     *       "apps_store": "https://apps.apple.com/us/app/haloyelow-customer/id6444608099",
     *       "join_date": "01 Jan, 1970",
     *       "following": 1,
     *       "username": "mahfud173",
     *   }
     * }
     */
    public function index(Request $request)
    {
        $linkPsApps = Setting::keys(['customer_play_store', 'customer_apps_store']);

        $user = $this->userService->findCustomerProfile($this->user()->id);
        $user->play_store = @$linkPsApps['customer_play_store'];
        $user->apps_store = @$linkPsApps['customer_apps_store'];
        $user->join_date = date('d M, Y', strtotime($user->join_date));
        $user->profile = asset($user->profile);
        $user->following = $user->following ?: 0;

        return $this->sendSuccess($user);
    }

    /**
     * List Blocked Account
     * 
     * @authenticated
     * @defaultParam
     * 
     * @queryParam limit integer optional default 10
     * @queryParam page integer optional default 1
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": [
     *       {
     *       "id": "99fc9e73-2bdf-4d45-8209-a3347a1eb85b",
     *       "type": "ba",
     *       "created_at": "2023-09-04 23:18:31",
     *       "name": "PT Taman Media Indonesia 2",
     *       "picture": "https://yelow-app-storage.s3.ap-southeast-1.amazonaws.com/cnako525c6GTGkUq1nefIJ38mXinpV5JovDMuuws.png"
     *       }
     *   ]
     * }
     */
    public function blockedAccount(Request $request)
    {
        $items = $this->blockedAccountService->findAllUserBlockedAccount($this->user()?->id, $request->get('limit', 10));
        return $this->sendSuccess(BlockedAccountItemResource::collection($items));
    }


    /**
     * Update Profile
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam name string required 
     * @bodyParam facebook string optional url
     * @bodyParam twitter string optional url
     * @bodyParam instagram string optional url
     * @bodyParam hide_profile boolean optional
     * @bodyParam profile file optional
     * 
     * @response {
     *   "status": 200,
     *   "message": "Successful"
     * }
     */
    public function updateProfile(Request $request, EditProfileUser $editProfileUser)
    {
        try {
            $editProfileUser->handle($request, $this->user()?->id);
            return $this->sendMessage('Successful');
        } catch (BadRequestException $e) {
            return $this->badRequest($e->getMessage());
        }
    }

    /**
     * Update BIO
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam bio string required  ex <code style="font-size:11px;margin-top:10px">Lorem Lorem Lorem&lt;br&gt;work at &lt;a class='username_tag' href='@axa'&gt;@axa&lt;/a&gt; 2017-2018&lt;br&gt;doctor</code>
     * 
     * @response {
     *   "status": 200,
     *   "message": "Successful"
     * }
     */
    public function updateBio(Request $request, EditBioUser $editBioUser)
    {
        try {
            $editBioUser->handle($request, $this->user()?->id);
            return $this->sendMessage('Successful');
        } catch (BadRequestException $e) {
            return $this->badRequest($e->getMessage());
        }
    }

    /**
     * Update Password
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam password string required
     * @bodyParam current_password string required
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "The current password you entered is incorrect."
     * }
     * @response {
     *   "status": 200,
     *   "message": "Your password was changed successfully."
     * }
     */
    public function updatePassword(Request $request, EditPasswordUser $editPasswordUser)
    {
        $this->validates([
            'password' => 'required|min:8|max:50|regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/',
            'current_password' => 'required|min:8|max:50|regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/',
        ]);
        try {
            $editPasswordUser->handle($request, $this->user()?->id);
            return $this->sendMessage('Your password was changed successfully.');
        } catch (BadRequestException $e) {
            return $this->badRequest($e->getMessage());
        }
    }

    /**
     * Update Language
     * 
     * @authenticated
     * @defaultParam
     * 
     * @response {
     *   "status": 200,
     *   "message": "Your language was changed successfully"
     * }
     */
    public function updateLang(Request $request)
    {
        User::whereId($this->user()->id)
            ->update([
                'lang' => $request->header('lang') ?: 'en'
            ]);
        return $this->sendMessage('Your language was changed successfully');
    }

    /**
     * Delete Account
     * 
     * @authenticated
     * @defaultParam
     * 
     * @response {
     *   "status": 200,
     *   "message": "This account has been deleted"
     * }
     */
    public function deleteAccount(Request $request, DeleteAccount $deleteAccount)
    {
        $deleteAccount->handle($this->user()->id);
        return $this->sendMessage('This account has been deleted');
    }

    /**
     * Logout
     * 
     * @authenticated
     * @defaultParam
     * 
     * @response {
     *   "status": 200,
     *   "message": "Good Bye !"
     * }
     */
    public function logout(Request $request)
    {
        $user =  User::findOrFail($this->user()->id);
        $user->update([
            'regid' => null,
            'platform' => null,
            'device_id' => null
        ]);
        JwtToken::blacklist();
        return $this->sendMessage('Good Bye !');
    }
}
