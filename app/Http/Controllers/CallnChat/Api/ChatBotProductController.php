<?php
namespace App\Http\Controllers\CallnChat\Api;

use App\Actions\ScheduleBot\ScheduleBotProductSchedule;
use Illuminate\Http\Request;
use App\Helpers\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use App\Actions\ScheduleBot\ScheduleBotRequestOtp;
use App\Actions\ScheduleBot\ScheduleBotValidateOtp;
use App\Actions\ScheduleBot\ScheduleBotChooseProduct;
use App\Actions\ScheduleBot\ScheduleBotBookingProduct;
use App\Http\Requests\Api\ScheduleBot\BookingProductScheduleRequest;

class ChatBotProductController
{
     public function chooseProduct(Request $request, ScheduleBotChooseProduct $scheduleBotChooseProduct)
     {
          $companyId = $request->companyId;
          $result = $scheduleBotChooseProduct->handle($request, $companyId);
          return response()->json($result);
     }

     public function scheduleLocation(Request $request,ScheduleBotProductSchedule $scheduleBotProductSchedule,$category){
          $companyId = $request->companyId;
          $productId = [];
          if($product = $request->product){
               $productId = explode(',',$product);
          }
          
          $result = $scheduleBotProductSchedule->findAllProductScheduleLocation($companyId,$category,$productId);
          return response()->json($result);
     }

     public function scheduleProfessionalName(Request $request,ScheduleBotProductSchedule $scheduleBotProductSchedule,$category){
          $companyId = $request->companyId;
          $productId = [];
          if($product = $request->product){
               $productId = explode(',',$product);
          }

          $result = $scheduleBotProductSchedule->findAllProductScheduleProfesionalType($companyId,$category,$request->location,$productId);
          return response()->json($result);
     }

     public function schedulePicName(Request $request,ScheduleBotProductSchedule $scheduleBotProductSchedule,$category){
          $companyId = $request->companyId;
          $productId = [];
          if($product = $request->product){
               $productId = explode(',',$product);
          }
          
          $result = $scheduleBotProductSchedule->findAllProductSchedulePicName($companyId,$category,$request->location,$request->professional,$productId);
          return response()->json($result);
     }

     public function scheduleDate(Request $request,ScheduleBotProductSchedule $scheduleBotProductSchedule,$category){
          $companyId = $request->companyId;
          $productId = [];
          if($product = $request->product){
               $productId = explode(',',$product);
          }

          $result = $scheduleBotProductSchedule->findAllProductScheduleDate($companyId,$category,$request->location,$request->professional,$request->pic_name,$productId);
          return response()->json($result);
     }

     public function requestOtp(Request $request, ScheduleBotRequestOtp $scheduleBotRequestOtp)
     {
          $companyId = $request->companyId;
          $result = $scheduleBotRequestOtp->handle($request, $companyId);
          return response()->json($result);
     }

     public function validateOtp(Request $request, ScheduleBotValidateOtp $scheduleBotValidateOtp)
     {
          try {
               $companyId = $request->companyId;
               $scheduleBotValidateOtp->handle($request, $companyId);
               return response()->json(true);
          } catch (BadRequestException $e) {
               return response()->json([
                    'message' => $e->getMessage()
               ], Response::HTTP_BAD_REQUEST);
          }
     }

     public function booking(BookingProductScheduleRequest $request, ScheduleBotBookingProduct $scheduleBotBookingProduct)
     {
          try {
               $companyId = $request->companyId;
               $scheduleBotBookingProduct->handle($request, $companyId);
               return response()->json(true);
          } catch (BadRequestException $e) {
               return response()->json([
                    'message' => $e->getMessage()
               ], Response::HTTP_BAD_REQUEST);
          } catch (\Exception $e) {
               return response()->json([
                    'message' => $e->getMessage()
               ], Response::HTTP_BAD_REQUEST);
          }
     }

}