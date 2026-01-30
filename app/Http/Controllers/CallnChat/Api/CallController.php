<?php

namespace App\Http\Controllers\CallnChat\Api;

use App\Actions\Call\CallActionTriggered;
use App\Actions\Call\InviteCallAgent;
use App\Actions\Call\RatingLiveSession;
use App\Actions\Call\RequestCallCallback;
use App\Helpers\BadRequestException;
use App\Http\Controllers\Controller;
use App\Models\Data\User;
use App\Services\CallnChat\ChatService;
use App\Services\Company\CompanyHelpdeskService;
use Illuminate\Http\Request;

class CallController extends Controller
{
    public function __construct(
        private CompanyHelpdeskService $companyHelpdeskService,
        private ChatService $chatService
    ) {
    }

    public function invite(Request $request, InviteCallAgent $inviteCallAgent, $agentId)
    {
        $companyId = $request->companyId;
        try {
            // $userId =  $request->header('user-account');
            $user = (object) [
                "profile" => config("services.placeholder_avatar"),
                "role" => "guest",
                "name" => "Guest",
                "id" => date('YmdHis')
            ];
            // if ($userId) {
            //     $userLogin = User::where('id', $userId)
            //         ->where('role', 'customer')
            //         ->select(['id', 'name', 'role', 'profile'])
            //         ->first();
            //     if ($userLogin) $user = $userLogin;
            // }
            $result = $inviteCallAgent->handle($agentId, $companyId, $user);
            return response()->json($result);
        } catch (BadRequestException $e) {
            return response()->json($e->getMessage(), 400);
        } catch (\Exception $e) {
            logger($e);
            return response()->json("Sorry for the inconvenience, we'll try to reconnecting . . .", 400);
        }
    }

    public function actionCall(Request $request, CallActionTriggered $callActionTriggered, $action, $callId)
    {
        try {
            $callActionTriggered->handle($request,'customer', $action, $callId);
            return response()->json('success');
        } catch (BadRequestException $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function rating(Request $request, RatingLiveSession $ratingLiveSession)
    {
        $request->validate([
            'company_id' => 'required',
            'rating' => 'required',
            'agent_id' => 'required',
        ]);
        $ratingLiveSession->handle($request);
        return response()->json("The assessment was successfully given, Thank you for giving an assessment");
    }

    public function requestCallback(Request $request, RequestCallCallback $requestCallCallback)
    {
        $companyId = $request->companyId;
        $requestCallCallback->handle($request, $companyId, $request->header('user-account'));
        return response()->json("Thank you! Our team will contact you soon");
    }
}
