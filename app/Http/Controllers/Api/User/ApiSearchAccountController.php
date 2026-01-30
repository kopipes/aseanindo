<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Api\Account\UsernameTagAccountResource;
use App\Services\Company\CompanyService;
use App\Services\User\UserService;
use App\Services\Util\BlockedAccountService;
use App\Services\Util\FollowingService;
use Illuminate\Http\Request;

/**
 * @group Accounts
 */
class ApiSearchAccountController extends ApiController
{
    public function __construct(
        private CompanyService $companyService,
        private UserService $userService,
        private BlockedAccountService $blockedAccountService,
        private FollowingService $followingService
    ) {
    }
    /**
     * Search Company and Customer
     * 
     * Home Searchbar
     * 
     * @authenticated
     * @defaultParam
     * 
     * @queryParam limit integer optional default 10
     * @queryParam page integer optional default 1
     * @queryParam search integer optional
     * @queryParam lat string required location user
     * @queryParam long string required location user
     * @queryParam filter[category_id][0] string optional array list to filter product by company category
     * @queryParam filter[category_id][1] string optional array list to filter product by company category
     * 
     * @response {
     *       "status": 200,
     *       "message": "success",
     *       "data": [{
     *           "id": "99f4b6a8-ad36-4271-b71e-692554032577",
     *           "name": "Sawyer and Mcleod Plc",
     *           "profile": "https://yelow-app-storage.s3.ap-southeast-1.amazonaws.com/cnako525c6GTGkUq1nefIJ38mXinpV5JovDMuuws.png",
     *           "type": "company",
     *           "info": "Sequi aliquip magni laudantium tempor laudantium recusandae Aut ipsam enim",
     *           "lat": "45.1",
     *           "lng": "15.2",
     *           "is_verified": 1,
     *           "username": "John",
     *           "distance": 2456.718569928482
     *       }]
     *  }
     */
    public function index(Request $request)
    {
        $this->validates([
            'long' => 'required',
            'lat' => 'required',
        ]);

        $items = $this->companyService->findAllUserAccountCompanyAndCustomer(
            userId: $this->user()?->id,
            latitude: $request->lat,
            longitude: $request->long,
            filter: $request->get('filter', []),
            search: $request->get('search', ''),
            limit: $request->get('limit', 10),
            page: $request->get('page', 1)
        );
        return $this->sendSuccess($items);
    }

    /**
     * Detail Customer
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "id": "99d8dcc8-a319-4bca-810e-bf5cce4ee77b",
     *       "name": "mahfud",
     *       "profile": "https://yelow-app-storage.s3.ap-southeast-1.amazonaws.com/cnako525c6GTGkUq1nefIJ38mXinpV5JovDMuuws.png",
     *       "bio": null,
     *       "join_date": "09 Aug, 2023",
     *       "following": 0,
     *       "username": "kawai_desu",
     *       "facebook": null,
     *       "twitter": null,
     *       "instagram": null,
     *       "is_block": false
     *   }
     * }
     */
    public function showCustomer(Request $request, $id)
    {
        $userLoginId = $this->user()?->id;
        $result = $this->userService->findDetailCustomer($id);
        if ($userLoginId) {
            $result->is_block  = $this->blockedAccountService->isAccountBlocked(
                blockedId: $id,
                blockedById: $userLoginId,
                blockedType: 'customer'
            ) ? true : false;
        }
        return $this->sendSuccess($result);
    }


    /**
     * Detail Company
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "id": "99c02fd2-7094-4064-a85f-e73bdf1cb50b",
     *       "name": "PT Taman Media Indonesia",
     *       "email": "laililmahvut@gmail.com",
     *       "profile": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/logo/xR1ZxtBPF6Al0romJDqaU15hksiw5pSO1Pk6SXPW.png",
     *       "address": "Jl. Bina Remaja No.6, Srondol Wetan, Kec. Banyumanik, Kota Semarang, Jawa Tengah 50363",
     *       "lat": "-6.2087634",
     *       "lng": "106.845599",
     *       "join_date": "28 Jul, 2023",
     *       "phone": "62984598458498",
     *       "description": "ImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfullImpactfull",
     *       "facebook": "www.fb.com",
     *       "twitter": "www.x.com",
     *       "instagram": "www.ig.com",
     *       "youtube": "www.yt.com",
     *       "username": "crocodicstudio",
     *       "follower": 0,
     *       "rating": 3.09,
     *       "is_block": false,
     *       "is_followed": false,
     *       "company_link": "https://haloyelow.com/customer/company/99c02fd2-7094-4064-a85f-e73bdf1cb50b"
     *   }
     * }
     */
    public function showCompany(Request $request, $id)
    {
        $userLoginId = $this->user()?->id;

        $result = $this->companyService->findDetailCompany($id);
        if ($userLoginId) {
            $result->is_block  = $this->blockedAccountService->isAccountBlocked(
                blockedId: $id,
                blockedById: $userLoginId,
                blockedType: 'ba'
            ) ? true : false;


            $result->is_followed  = $this->followingService->isAccountFollowed(
                companyId: $id,
                userId: $userLoginId,
            ) ? true : false;
        }
        return $this->sendSuccess($result);
    }


    /**
     * Search Username
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam type string required in ba or customer
     * 
     * @queryParam search string optional
     * 
     * @response {
     *       "status": 200,
     *       "message": "success",
     *       "data": [{
     *           "id": "99c02fd2-7094-4064-a85f-e73bdf1cb50b",
     *           "name": "PT Taman Media Indonesia",
     *           "picture": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/logo/xR1ZxtBPF6Al0romJDqaU15hksiw5pSO1Pk6SXPW.png",
     *           "tag": "<a class=\"username_tag\" data-id=\"99c02fd2-7094-4064-a85f-e73bdf1cb50b\" data-type=\"ba\">@crocodicstudio</a>"
     *       }]
     * }
     */
    public function username(Request $request,$type)
    {
        $items = [];
        $search = $request->get('search','');
        $userId = $this->user()?->id;
        if($type==='ba'){
            $items = $this->companyService->findAllUsernameCompany($search,$userId);
        }else if($type==='customer'){
            $items = $this->userService->findAllUsernameCompany($search,$userId);
        }

        return $this->sendSuccess(UsernameTagAccountResource::collection($items));

    }
}
