<?php
namespace App\Actions\ScheduleBot;

use App\Models\Data\TicketScheduleDetail;
use Illuminate\Support\Facades\DB;

class ScheduleBotProductSchedule
{
     public function findAllProductScheduleLocation($companyId, $category,$productId = [])
     {
          return DB::table('products_schedule_detail')
               ->join('company_products', 'company_products.id', 'products_schedule_detail.product_id')
               ->where('company_products.company_id', $companyId)
               ->where('company_products.type', 'inbound')
               ->where('company_products.category', $category)
               ->when(count($productId),fn($query)=>$query->whereIn('company_products.id',$productId))
               ->whereDate('products_schedule_detail.end_date','>=',date('Y-m-d'))
               ->whereNotNull('company_products.chatbot_forms')
               ->whereNotNull('products_schedule_detail.counseling_time')
               ->whereNull('company_products.deleted_at')
               ->select(
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(products_schedule_detail.location, '$[0]')) AS value")
               )
               ->distinct()
               ->get();
     }

     public function findAllProductScheduleProfesionalType($companyId, $category, $location,$productId = [])
     {
          return DB::table('products_schedule_detail')
               ->join('company_products', 'company_products.id', 'products_schedule_detail.product_id')
               ->where('company_products.type', 'inbound')
               ->where('company_products.company_id', $companyId)
               ->where('company_products.category', $category)
               ->when(count($productId),fn($query)=>$query->whereIn('company_products.id',$productId))
               ->whereDate('products_schedule_detail.end_date','>=',date('Y-m-d'))
               ->where('products_schedule_detail.location', 'like', '%"' . $location . '"%')
               ->whereNotNull('company_products.chatbot_forms')
               ->whereNotNull('products_schedule_detail.counseling_time')
               ->whereNull('company_products.deleted_at')
               ->select(
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(products_schedule_detail.professional_type, '$[0]')) AS value")
               )
               ->distinct()
               ->get();
     }

     public function findAllProductSchedulePicName($companyId, $category, $location, $professional,$productId = [])
     {
          return DB::table('products_schedule_detail')
               ->join('company_products', 'company_products.id', 'products_schedule_detail.product_id')
               ->where('company_products.type', 'inbound')
               ->where('company_products.company_id', $companyId)
               ->where('company_products.category', $category)
               ->when(count($productId),fn($query)=>$query->whereIn('company_products.id',$productId))
               ->whereDate('products_schedule_detail.end_date','>=',date('Y-m-d'))
               ->where('products_schedule_detail.location', 'like', '%"' . $location . '"%')
               ->where('products_schedule_detail.professional_type', 'like', '%"' . $professional . '"%')
               ->whereNotNull('company_products.chatbot_forms')
               ->whereNotNull('products_schedule_detail.counseling_time')
               ->whereNull('company_products.deleted_at')
               ->select([
                    'products_schedule_detail.pic_name as value'
               ])
               ->distinct()
               ->get();
     }


     public function findAllProductScheduleDate($companyId, $category, $location, $professional, $picName,$productId = [])
     {
          $products = DB::table('products_schedule_detail')
               ->join('company_products', 'company_products.id', 'products_schedule_detail.product_id')
               ->where('company_products.type', 'inbound')
               ->where('company_products.company_id', $companyId)
               ->where('company_products.category', $category)
               ->when(count($productId),fn($query)=>$query->whereIn('company_products.id',$productId))
               ->where('products_schedule_detail.location', 'like', '%"' . $location . '"%')
               ->where('products_schedule_detail.professional_type', 'like', '%"' . $professional . '"%')
               ->where('products_schedule_detail.pic_name', $picName)
               ->whereNotNull('company_products.chatbot_forms')
               ->whereNull('company_products.deleted_at')
               ->select([
                    'products_schedule_detail.counseling_time',
                    'company_products.id'
               ])
               ->distinct()
               ->get();

          $bookedProduct = TicketScheduleDetail::query()
               ->where('company_id', $companyId)
               ->whereIn('product_id', $products->pluck('id'))
               ->get();
          $counseling_times = [];
          foreach ($products as $product) {
               if (!$product->counseling_time) {
                    continue;
               }
               foreach (json_decode($product->counseling_time) as $date => $value) {
                    if ($date == 'all') {
                         continue;
                    }
                    $times = [];
                    $bookeds = $bookedProduct->where('counseling_date', $date);
                    foreach ($value as $time) {
                         $bookedTime = $bookeds
                              ->filter(function ($booked) use ($time) {
                                   return $booked->counseling_time['start'] == $time->start && $booked->counseling_time['end'] == $time->end;
                              })->sum('number');
                         $time->booked = $bookedTime;
                         $time->is_full = false;
                         $time->enable = true;
                         if($date==date('Y-m-d') && $time->end<=date('H:i')){
                              $time->enable = false;
                         }
                         if($bookedTime>=intval($time->max)){
                              $time->is_full = true;
                         }
                         $times[] = $time;
                    }
                    $counseling_times[$date] = [
                         'id' => $product->id,
                         'times' => $times
                    ];
               }
          }
          return $counseling_times;
     }
}