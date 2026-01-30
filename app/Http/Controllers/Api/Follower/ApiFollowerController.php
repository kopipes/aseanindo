<?php

namespace App\Http\Controllers\Api\Follower;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Services\Util\FollowingService;
use Illuminate\Http\Request;

/**
 * @group Follower
 */
class ApiFollowerController extends ApiController
{
    public function __construct(
        private FollowingService $followingService
    ) {
    }
    /**
     * List Follower Company
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": [
     *       {
     *       "id": "9a010277-edfb-485a-931b-f1d95af8414c",
     *       "name": "mahfud",
     *       "profile": "http://proyek.test/yelow-premium/callnchat-and-api/public/",
     *       "email": "mahfud@mailinator.com",
     *       "phone": "629484884848",
     *       "username": "mahfud173"
     *       }
     *   ]
     * }
     */
    public function followerCompany(Request $request, $id)
    {
        $items = $this->followingService->findAllFollowerCompany(
            companyId: $id,
            limit: $request->get('limit', 10)
        );
        return $this->sendSuccess($items);
    }

    /**
     * List Followed Customer
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required customer id or me if user login
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": [
     *       {
     *       "id": "99c02fd2-7094-4064-a85f-e73bdf1cb50b",
     *       "name": "PT Taman Media Indonesia",
     *       "profile": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/logo/xR1ZxtBPF6Al0romJDqaU15hksiw5pSO1Pk6SXPW.png",
     *       "username": "crocodicstudio",
     *       "is_block": false,
     *       "is_followed": true
     *       }
     *   ]
     * }
     */
    public function followedCustomer(Request $request, $id)
    {
        $userLoginId = $this->user()?->id;
        if ($id === 'me') $id = $userLoginId;
        $items = $this->followingService->findAllFollowedCustomer(
            customerId: $id,
            userLoginId: $userLoginId,
            limit: $request->get('limit', 10)
        );
        return $this->sendSuccess($items);
    }

    /**
     * Follow/Un Follow BA
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required company id
     * @pathParam type string required in follow or un-follow
     * 
     * @response {
     *   "status": 200,
     *   "message": "BA has been followed"
     *  }
     */
    public function followCompany(Request $request, $id, $type)
    {
        $userId = $this->user()->id;
        if ($type === 'follow') {
            $this->followingService->followBa($userId, $id);
            return $this->sendMessage('BA has been followed');
        }


        $this->followingService->unFollowBa($userId, $id);
        return $this->sendMessage('BA has been un followed');
    }
}
