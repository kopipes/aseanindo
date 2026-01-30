<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Services\Util\BlockedAccountService;
use App\Services\Util\FavoriteProductService;
use App\Services\Util\FollowingService;
use App\Services\Util\ReportSpamService;
use Illuminate\Http\Request;


/**
 * @group Accounts
 */
class ApiAccountController extends ApiController
{
    public function __construct(
        private ReportSpamService $reportSpamService,
        private BlockedAccountService $blockedAccountService,
        private FavoriteProductService $favoriteProductService,
        private FollowingService $followingService
    ) {
    }
    /**
     * Report Account
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam type string required in company or customer
     * @pathParam id string required id customer or id company
     * 
     * @requestBody multipart/form-data
     * @bodyParam reason string required
     * 
     * @response {
     *   "status": 200,
     *   "message": "Report successfully"
     * }
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "You have reported before!"
     * }
     */
    public function reportAccount(Request $request, $type, $id)
    {
        $this->validates(['reason' => 'required']);
        $userId = $this->user()?->id;
        if (!$this->reportSpamService->findReport($id, $type, $userId)) {
            $this->reportSpamService->reportContent(
                accountId: $id,
                accountType: $type,
                reportBy: $userId,
                reason: $request->reason
            );

            return $this->sendMessage('Report successfully');
        }
        return $this->badRequest('You have reported before!');
    }

    /**
     * Block Account
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam type string required in company or customer
     * @pathParam id string required id customer or id company
     * 
     * @response {
     *   "status": 200,
     *   "message": "Account has been blocked!"
     * }
     */
    public function blockAccount(Request $request, $type, $id)
    {
        $userId = $this->user()?->id;
        $this->blockedAccountService->blockAccount(
            blockedBy: $userId,
            blockedId: $id,
            type: $type
        );
        if ($type === 'company') {
            $this->favoriteProductService->removeAllFavoriteProduct($userId, $id);
            $this->followingService->unFollowBa($userId, $id);
        }

        return $this->sendMessage('Account has been blocked!');
    }

    /**
     * Un Block Account
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam type string required in company or customer
     * @pathParam id string required id customer or id company
     * 
     * @response {
     *   "status": 200,
     *   "message": "Account has been un blocked!"
     * }
     */
    public function unBlockAccount(Request $request, $type, $id)
    {
        $userId = $this->user()?->id;
        $this->blockedAccountService->unBlockAccount(
            blockedBy: $userId,
            blockedId: $id,
            type: $type
        );

        return $this->sendMessage('Account has been un blocked!');
    }
}
