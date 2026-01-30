<?php

namespace App\Http\Controllers\Api\Rating;

use App\Helpers\BroadcastNotification;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Api\Util\DetailRatingResource;
use App\Models\Data\User;
use App\Services\Ticket\TicketService;
use App\Services\Util\NotificationService;
use App\Services\Util\RatingService;
use Illuminate\Http\Request;

/**
 * @group Ratings
 */
class ApiRatingController extends ApiController
{
    public function __construct(
        private RatingService $ratingService,
        private NotificationService $notificationService,
        private TicketService $ticketService
    ) {
    }
    /**
     * List Rating Company
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required
     * 
     * @queryParam limit integer optional default 10
     * @queryParam page integer optional default 1
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": [
     *       {
     *       "id": "99fac456-6c13-4c36-a876-87865c7e8328",
     *       "name": "Mr. Jo Lind MD",
     *       "profile": "https:://img/default-avatar.png",
     *       "email": "karen39@example.net",
     *       "phone": "951.231.9416",
     *       "rating": 3,
     *       "review": "oke cut",
     *       "username": "mr._jo_998"
     *       }
     *   ]
     * }
     */
    public function ratingsCompany(Request $request, $id)
    {
        $ratings = $this->ratingService->findAllCompanyRating(
            companyId: $id,
            limit: $request->get('limit', 10)
        );
        return $this->sendSuccess($ratings);
    }


    /**
     * Detail Rating
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required rating id
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "company": {
     *       "id": "99c02fd2-7094-4064-a85f-e73bdf1cb50b",
     *       "name": "PT Taman Media Indonesia",
     *       "image": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/logo/xR1ZxtBPF6Al0romJDqaU15hksiw5pSO1Pk6SXPW.png"
     *       },
     *       "category": "Form",
     *       "date": "28 Jul 2023",
     *       "time": "09:15 AM",
     *       "created_date": "2828, Fri Jul 2023 09:15:55",
     *       "agent": "Bobby Moen DDS",
     *       "note": "",
     *       "ticket_number": "PRO20230818103040679",
     *       "product": {
     *       "id": "99d6f51a-e135-4987-924b-54c79152f823",
     *       "image": "http://proyek.test/yelow-premium/callnchat-and-api/public/",
     *       "name": "Adolfo Maggio",
     *       "type": "schedule_professional",
     *       "doctor_image": "http://proyek.test/yelow-premium/callnchat-and-api/public/img/default-avatar.png",
     *       "doctor_name": "Adolfo Maggio",
     *       "medical_specialist": "[\"Dokter Bedah\", \"Dokter Gigi\"]"
     *       },
     *       "booking_details": null,
     *       "rating": null,
     *       "review": "Eichmann LLC Gabrielle Satterfield lorem ipsum",
     *       "schedule_type": "doctor",
     *       "category_status": null
     *   }
     * }
     */
    public function show(Request $request, $id)
    {
        if (!$rating = $this->ratingService->findDetailRating($id, $this->user()?->id)) {
            return $this->badRequest('Detail rating not found');
        }
        return $this->sendSuccess(new DetailRatingResource($rating));
    }


    /**
     * Review Rating
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required rating id, parent id if from inbox/notification
     * 
     * @requestBody multipart/form-data
     * @bodyParam rating integer required
     * @bodyParam review string optional
     * 
     * @response {
     *   "status": 200,
     *   "message": "The assessment was successfully given, Thank you for giving an assessment"
     * }
     */
    public function review(Request $request, $id)
    {
        $this->validates(['rating' => 'required']);

        $userId = $this->user()?->id;
        $this->ratingService->updateRating($id, $userId, [
            'review' => $request->review,
            'rating' => $request->rating
        ]);

        $this->notificationService->updateDetailRatingNotification($userId, $id, $request->rating);

        return $this->sendMessage(__('rating_success'));
    }


    /**
     * Review Call or Chat
     * 
     * @authenticated
     * @defaultParam
     * 
     * 
     * @pathParam ticket_id string required
     * 
     * @requestBody multipart/form-data
     * @bodyParam rating integer required
     * @bodyParam review string optional
     * 
     * @response {
     *   "status": 200,
     *   "message": "The assessment was successfully given, Thank you for giving an assessment"
     * }
     */
    public function reviewCallOrChat(Request $request, $ticket_id)
    {
        $this->validates(['rating' => 'required']);

        $customerId = $this->user()?->id;
        $ticket = $this->ticketService->findDataTicketByTicketIdAndCustomerId($ticket_id, $customerId);
        $rating = $this->ratingService->updateRatingByTicket($ticket, $customerId, [
            'review' => $request->review,
            'rating' => $request->rating
        ]);

        $this->notificationService->updateDetailRatingNotification($customerId, $rating->id, $request->rating);

        $customer = User::where('id', $customerId)
            ->where('role', 'customer')
            ->select(['id', 'regid', 'platform'])
            ->first();

        BroadcastNotification::to($customer)
            ->dispatch([
                'type' => 'rating_consultation',
                'title' => $ticket->company_name,
                'message' => $ticket->product_name,
                'icon' => $ticket->company_logo,
                'parent_id' => $rating->id,
                'ticket_id' => $ticket->id,
                'details' => [
                    'rating' => $request->rating
                ],
            ]);

        return $this->sendMessage(__('rating_success'));
    }
}
