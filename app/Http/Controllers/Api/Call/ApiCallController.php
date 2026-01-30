<?php

namespace App\Http\Controllers\Api\Call;

use App\Actions\Call\CallActionTriggered;
use App\Actions\Call\InviteCallAgent;
use App\Actions\Call\InviteCallCustomer;
use App\Actions\Call\RequestCallCallback;
use App\Helpers\BadRequestException;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Api\Call\DetailCallResource;
use App\Models\Data\User;
use App\Services\CallnChat\CallService;
use App\Services\Util\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @group Call
 */
class ApiCallController extends ApiController
{
    public function __construct(
        private NotificationService $notificationService,
        private CallService $callService
    ) {
    }
    /**
     * Call History
     * 
     * Response type in <code style="font-size:11px;margin-top:5px">Incoming Call | Outgoing Call | Outgoing Missed Call |  Incoming Missed Call | Callback</code>
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
     *       "id": "0250e5db-6a00-48eb-8342-08673aa31f1a",
     *       "company_id": "99c02fd2-7094-4064-a85f-e73bdf1cb50b",
     *       "picture": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/logo/xR1ZxtBPF6Al0romJDqaU15hksiw5pSO1Pk6SXPW.png",
     *       "name": "PT Taman Media Indonesia",
     *       "created_at": "2022-06-17 11:22:34",
     *       "type": "Incoming Call",
     *       "title": "Missed call",
     *       "description": "Anda telah di hubungi help desk PT Taman Media Indonesia"
     *       }
     *   ]
     *  }
     */
    public function index(Request $request)
    {
        $userId = $this->user()?->id;
        $items = $this->notificationService->findAllCallHistory($userId, $request->get('limit', 10))
            ->each(function ($row) {
                $row->picture = asset($row->picture);
            });
        return $this->sendSuccess($items);
    }

    /**
     * Detail Call
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required call id
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "call_id": "99ff0dd0-a7ab-4155-a627-87157e5ff289",
     *       "status": "missed",
     *       "channel_name": "guest_to_dr_haley_reichert",
     *       "agent_token": "0064572c58dc2f2491eb5a81b2013bb6957IABNvVonCZbuS3mA//A7Jn/sg6R/X9I8T8BuCRgHn/fZw6NVo4cnFEVIIgDvNgAAapztZAQAAQCq8+xkAwCq8+xkAgCq8+xkBACq8+xk",
     *       "customer_token": "0064572c58dc2f2491eb5a81b2013bb6957IADzjzopUscbxMzI6wwJQvOQFWhMYsCs/illI1rDVARS1aNVo4fBmb6cIgD3AAAAapztZAQAAQCq8+xkAwCq8+xkAgCq8+xkBACq8+xk",
     *       "agent_uuid": 2822972610,
     *       "customer_uuid": 237069496,
     *       "call_type": "Missed Call",
     *       "start_at": "2023-08-28 07:21:14",
     *       "company_id": "99c02fd2-7094-4064-a85f-e73bdf1cb50b",
     *       "is_sip": false,
     *       "spv_join_at": "2023-09-13 21:10:04",
     *       "spv": {
     *       "id": "99c87ee3-b301-4500-ad84-369c37950520",
     *       "name": "Bobby Moen DDS",
     *       "email": "marilie49@example.com",
     *       "phone": "+17436441204",
     *       "profile": "http://localhost:8000/uploads/profile/KnBTjqx79w22GLBV0JjKxDrdxZtTRl7reZjBvLLS.jpg"
     *       },
     *       "agent": {
     *       "id": "99c87ee3-abd7-4b52-9b4d-b42199f69ed5",
     *       "name": "Dr. Haley Reichert",
     *       "email": "langworth.santos@example.net",
     *       "phone": "1-903-202-1677",
     *       "profile": "http://localhost:8000/uploads/profile/KnBTjqx79w22GLBV0JjKxDrdxZtTRl7reZjBvLLS.jpg"
     *       }
     *   }
     * }
     */
    public function show(Request $request, $id)
    {
        $call = $this->callService->findDetailCall($id);

        return $this->sendSuccess(new DetailCallResource($call));
    }


    /**
     * Request Callback
     * 
     * @authenticated
     * @defaultParam
     * 
     * @requestBody multipart/form-data
     * @bodyParam helpdesk_category_id[0] string required helpdesk id
     * @bodyParam helpdesk_category_id[1] string optional helpdesk id
     * 
     * @pathParam company_id string required
     * 
     * @response {
     *   "status": 200,
     *   "message": "Thank you! Our team will contact you soon"
     * }
     */
    public function requestCallback(Request $request, RequestCallCallback $requestCallCallback, $companyId)
    {
        $this->validates(['helpdesk_category_id' => 'required|array']);
        $requestCallCallback->handle($request, $companyId, $this->user()?->id);

        return $this->sendMessage('Thank you! Our team will contact you soon');
    }

    /**
     * Call Agent
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam agent_id string required
     * @pathParam company_id string required
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "call_id": "9a0f3664-4b08-4ec8-8481-9e40cdb93ca2",
     *       "channel_name": "muhammad_lailil_mahfud_to_bobby_moen_dds",
     *       "token": "0064572c58dc2f2491eb5a81b2013bb6957IAAmlHevRGQ2MHO1JxU4LlX7U9VyuXhdGJiT1MO2ow7ceZa3D6p3myzFIgCbqwAAXTP4ZAQAAQCdivdkAwCdivdkAgCdivdkBACdivdk",
     *       "uuid": 237069496,
     *       "company_id": "99c02fd2-7094-4064-a85f-e73bdf1cb50b",
     *       "call_session": "NDU3MmM1OGRjMmYyNDkxZWI1YTgxYjIwMTNiYjY5NTc=",
     *       "max_ringing": 30
     *   }
     * }
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "The agent is not available now. Please try again later."
     * }
     */
    public function inviteAgent(Request $request, InviteCallAgent $inviteCallAgent,  $agentId, $companyId)
    {
        try {
            $user = null;
            $userId = $this->user()?->id;
            if($userId){
                $user = User::where('id', $userId)
                        ->where('role', 'customer')
                        ->select(['id', 'name', 'role', 'profile'])
                        ->first();
            }
            if(!$user){
                $user = (object) [
                    "profile" => config("services.placeholder_avatar"),
                    "role" => "guest",
                    "name" => "Guest",
                    "is_pstn" => true,
                    "id" => date('YmdHis')
                ];
            }

            $result = $inviteCallAgent->handle($agentId, $companyId, $user);

            return $this->sendSuccess($result);
        } catch (BadRequestException $e) {
            return $this->badRequest($e->getMessage());
        } catch (\Exception $e) {
            logger($e);
            return $this->badRequest("Sorry for the inconvenience, we'll try to reconnecting . . .");
        }
    }

    /**
     * Call Customer
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam customer_id string required
     * 
     * @requestBody multipart/form-data
     * @bodyParam source string required in From Web | From App
     * @bodyParam is_sip boolean optional
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "call_id": "9a0f3664-4b08-4ec8-8481-9e40cdb93ca2",
     *       "channel_name": "muhammad_lailil_mahfud_to_bobby_moen_dds",
     *       "token": "0064572c58dc2f2491eb5a81b2013bb6957IAAmlHevRGQ2MHO1JxU4LlX7U9VyuXhdGJiT1MO2ow7ceZa3D6p3myzFIgCbqwAAXTP4ZAQAAQCdivdkAwCdivdkAgCdivdkBACdivdk",
     *       "uuid": 237069496,
     *       "company_id": "99c02fd2-7094-4064-a85f-e73bdf1cb50b",
     *       "call_session": "NDU3MmM1OGRjMmYyNDkxZWI1YTgxYjIwMTNiYjY5NTc=",
     *       "max_ringing": 30
     *   }
     * }
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "The Customer already contacted with another agent"
     * }
     */
    public function callCustomer(Request $request, InviteCallCustomer $inviteCallCustomer, $customerId)
    {
        try {
            $this->validates(['source' => 'required|in:From Web,From App,From SIP,From Whatsapp']);

            $user = $this->user();
            $agentId = $user?->id;
            $companyId = $user?->company_id;
            if ($agentId && $companyId) {
                $result = $inviteCallCustomer->handle($agentId, $companyId, $customerId, $request);
                return $this->sendSuccess($result);
            }

        } catch (BadRequestException $e) {
            logger($e);
            return $this->badRequest($e->getMessage());
        } catch (\Exception $e) {
            logger($e);
        }
        return $this->badRequest("Sorry for the inconvenience, we'll try to reconnecting . . .");
    }

    /**
     * Call Action
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam call_id string required
     * @pathParam action string required in <code style="font-size:12px;margin-top:5px">'accept', 'reject', 'cancel', 'end', 'missed', 'disconnect', 'spv-join', 'spv-leave', 'spv-reject','invite-spv'</code>
     * 
     * @requestBody multipart/form-data
     * @bodyParam agent_extension string optional extension agent if call is sip and action is end
     * @bodyParam destination string optional customer phone number if call is sip and action is end
     * 
     * @response {
     *   "status": 200,
     *   "message": "success"
     * }
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "Call not found."
     * }
     */
    public function callActionDo(Request $request, CallActionTriggered $callActionTriggered, $callId, $action)
    {
        try {
            $user = $this->user();
            $callActionTriggered->handle($request,$user->permission, $action, $callId, $user->id);
            return $this->sendMessage('success');
        } catch (BadRequestException $e) {
            return $this->badRequest($e->getMessage());
        } catch (\Exception $e) {
            logger($e);
        }
    }
}
