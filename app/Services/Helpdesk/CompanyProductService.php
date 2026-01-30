<?php

namespace App\Services\Helpdesk;

use App\Models\Data\CompanyProduct;
use App\Services\Util\FavoriteProductService;
use App\Services\Util\FollowingService;

class CompanyProductService
{
     public function __construct(
          public $model = CompanyProduct::class,
          private $favoriteProductService = new FavoriteProductService,
          private $followingService = new FollowingService
     ) {
     }

     public function findAllProduct($blockedBaId, $category, $filter, $search, $limit)
     {
          $category_id = @$filter['category_id'];
          $companyId = @$filter['company_id'];
          return $this->model::with([
               'company:logo as profile,name,city,address,companies.id,companies.username',
               'helpdeskName:product_id,name,helpdesk_id'
          ])
               ->when($search, function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                    $query->orWhere('description', 'like', "%{$search}%");
                    $query->orWhereRelation('helpdeskName', fn ($filterSearch) => $filterSearch->where('name', 'like', "%{$search}%"));
               })
               ->whereRelation('company', function ($query) use ($category_id) {
                    $query->where('status', 'approved');
                    $query->when($category_id, fn ($filter) => $filter->whereIn('company_category_id', $category_id));
               })
               ->where('is_kontakkami', false)
               ->when($companyId, fn ($query) => $query->where('company_id', $companyId))
               ->when($blockedBaId, fn ($query) => $query->whereNotIn('company_id', $blockedBaId))
               ->where('category', $category)
               ->select(['id', 'image', 'name', 'description', 'created_at', 'type', 'company_id'])
               ->paginate($limit);
     }


     public function findAllFollowingProduct($customerId, $limit)
     {
          return $this->model::with([
               'company:logo as profile,name,city,address,companies.id,companies.username',
               'helpdeskName:product_id,name,helpdesk_id'
          ])
               ->whereRelation('company', function ($query) {
                    $query->where('status', 'approved');
               })
               ->where('is_kontakkami', false)
               ->whereIn('company_id', $this->followingService->findAllFollowedId($customerId))
               ->where('category', 'general')
               ->select(['id', 'image', 'name', 'description', 'created_at', 'type', 'company_id'])
               ->paginate($limit);
     }


     public function findAllFavoriteProductCustomer($customerId, $limit)
     {
          return $this->model::with([
               'company:logo as profile,name,city,address,companies.id,companies.username',
               'helpdeskName:product_id,name,helpdesk_id'
          ])
               ->where('category', 'general')
               ->whereRelation('company', function ($query) {
                    $query->where('status', 'approved');
               })
               ->whereIn('id', $this->favoriteProductService->findAllProductId($customerId))
               ->select(['id', 'image', 'name', 'description', 'created_at', 'type', 'company_id'])
               ->paginate($limit);
     }

     public function findProductById($id)
     {
          return $this->model::with([
               'company:logo as profile,name,city,address,companies.id,companies.username',
               'helpdeskName:product_id,name,helpdesk_id'
          ])
               ->where('id', $id)
               ->select(['id', 'image', 'name', 'description', 'created_at', 'type', 'company_id'])
               ->first();
     }

     public function findAllByCategory($companyId,$category,$productId = []){
          $currentDate = date('Y-m-d H:i:s');
          return $this->model::query()
               ->with([
                    'detail:product_id,location,start_date,end_date,start_time,end_time,max_booking',
                    'bookings'
               ])
               ->where('company_id', $companyId)
               ->where('category', $category)
               ->where('type', 'inbound')
               ->when(count($productId),fn($query)=>$query->whereIn('id',$productId))
               ->whereNotNull('chatbot_forms')
               ->select(['id', 'name', 'image', 'description', 'form_template_id', 'category'])
               ->latest()
               ->get()
               ->each(function ($row) {
                    $row->max_booking = 1;
                    if ($row->detail) {
                         $row->start_date = date('Y-m-d', strtotime($row->detail->start_date)) . ' ' . date('H:i:s', strtotime($row->start_time));
                         $row->end_date = date('Y-m-d', strtotime($row->detail->end_date)) . ' ' . date('H:i:s', strtotime($row->end_time));
                         $row->detail->start_date = date('d M Y', strtotime($row->detail->start_date));
                         $row->detail->end_date = date('d M Y', strtotime($row->detail->end_date));
                         $row->max_booking = $row->detail?->max_booking ?: 0;
                    }
                    $row->image = asset($row->image ?: config('services.placeholder_avatar'));
                    if ($row->category == 'general') {
                         $row->start_date = now()->addMonths(-1)->format('Y-m-d H:i:s');
                         $row->end_date = now()->addMonth()->format('Y-m-d H:i:s');
                         $row->bookings_count = 0;
                    } else {
                         $row->bookings_count = $row->bookings->sum('number');
                    }
                    unset($row->bookings);
               })
               ->where('start_date', '<=', $currentDate)
               ->where('end_date', '>=', $currentDate)
               ->filter(fn($row) => $row->max_booking > $row->bookings_count)
               ->values();
     }
}
