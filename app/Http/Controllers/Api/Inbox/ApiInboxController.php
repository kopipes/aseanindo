<?php

namespace App\Http\Controllers\Api\Inbox;

use App\Http\Controllers\ApiController;
use App\Services\Util\NotificationService;
use Illuminate\Http\Request;

/**
 * @group Inbox - Notification
 */
class ApiInboxController extends ApiController
{
    public function __construct(
        private NotificationService $notificationService
    ) {
    }
    /**
     * 
     * List Inbox - Notification
     * 
     * @authenticated
     * @defaultParam
     * 
     * @queryParam limit integer optional default 10
     * @queryParam page integer optional default 1
     * @queryParam search integer optional
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": [
     *       {
     *       "id": "99fac456-6ea5-47b3-9022-0e2fca398652",
     *       "company_id": "99c02fd2-7094-4064-a85f-e73bdf1cb50b",
     *       "picture": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/logo/xR1ZxtBPF6Al0romJDqaU15hksiw5pSO1Pk6SXPW.png",
     *       "created_at": "2023-08-25 21:12:28",
     *       "parent_id": "99fac456-6c13-4c36-a876-87865c7e8328",
     *       "name": "PT Taman Media Indonesia",
     *       "type": "rating_product",
     *       "title": "PT Taman Media Indonesia",
     *       "description": null,
     *       "details": {
     *           "rating": "3"
     *       },
     *       "is_read": 0
     *       }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        $items = $this->notificationService->findAllNotification(
            customerId: $this->user()?->id,
            search: $request->get('search', ''),
            limit: $request->get('limit', 10)
        )->each(function ($item) {
            $item->picture = asset($item->picture);
        });

        return $this->sendSuccess($items);
    }

    /**
     * Count - Badge Notification
     * 
     * @authenticated
     * @defaultParam
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": "99+"
     * }
     */
    public function count(Request $request)
    {
        $total = $this->notificationService->countAllNotification($this->user()?->id);
        $total = $total >= 100 ? '99+' : strval($total);
        return $this->sendSuccess($total);
    }


    /**
     * Read Inbox -  Notification
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required notification/inbox id
     * 
     * @response {
     *   "status": 200,
     *   "message": "Success"
     * }
     */
    public function read(Request $request, $id)
    {
        $this->notificationService->readNotification($this->user()?->id, $id);
        return $this->sendMessage('Success');
    }

    /**
     * Delete Inbox - Notification
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required notification/inbox id
     * 
     * @response {
     *   "status": 200,
     *   "message": "Success"
     * }
     */
    public function destroy(Request $request, $id)
    {
        $this->notificationService->deleteNotification($this->user()?->id, $id);
        return $this->sendMessage('Success');
    }
}
