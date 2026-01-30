<?php

namespace App\Services\Company;

use App\Models\Company\CompanyProfile;
use App\Models\Data\CompanyProduct;
use App\Models\Data\HelpdeskCategory;
use App\Models\Data\UserHelpdesk;
use App\Models\Util\Setting;

class CompanyHelpdeskService
{
     public function __construct(
          public $product = CompanyProduct::class,
          public $helpdesk = HelpdeskCategory::class,
          public $helpdeskUser = UserHelpdesk::class,
     ) {
     }

     public function findAllProduct($companyId)
     {
          $currentDate = date('Y-m-d H:i:s');
          return $this->product::query()
               ->leftJoin("products_schedule_detail as schedule", "schedule.product_id", "company_products.id")
               ->where('company_products.company_id', $companyId)
               ->where('company_products.type', 'inbound')
               ->where('company_products.is_hidden', false)
               ->where('company_products.is_kontakkami', false)
               ->select([
                    'company_products.id',
                    'company_products.name',
                    'company_products.description',
                    'company_products.created_at',
                    'company_products.image',
                    'company_products.category',
                    'company_products.faq_id',
                    'schedule.end_date',
                    'schedule.start_date',
                    'schedule.start_time',
                    'schedule.end_time',
                    'schedule.max_booking'
               ])
               ->with(['bookings'])
               ->orderBy('company_products.created_at', 'desc')
               ->get()
               ->each(function ($product) {
                    $product->image = asset($product->image ?: config('services.placeholder_avatar'));
                    $product->start_date = date('Y-m-d H:i:s', strtotime($product->created_at)); //.' '.date('H:i:s',strtotime($product->start_time));
                    $product->end_date = date('Y-m-d', strtotime($product->end_date)) . ' ' . date('H:i:s', strtotime($product->end_time));
                    if ($product->category == 'general') {
                         $product->start_date = now()->addMonths(-1)->format('Y-m-d H:i:s');
                         $product->end_date = now()->addMonth()->format('Y-m-d H:i:s');
                         $product->bookings_count = 0;
                         $product->max_booking = 1;
                    }else{
                         $product->bookings_count = $product->bookings->sum('number');  
                    }
                    unset($product->bookings);
               })
               ->where('start_date', '<=', $currentDate)
               ->where('end_date', '>=', $currentDate)
               // ->filter(fn($row) => $row->max_booking > $row->bookings_count)
               ->values();
     }

     public function findAllHelpdesk($companyId)
     {
          return $this->helpdesk::query()
               ->with([
                    'sub' =>
                         function ($query) {
                              $query->where('status', 'active');
                              $query->where('is_hidden',false);
                         }
               ])
               ->where('company_id', $companyId)
               ->where('is_kontakkami', false)
               ->where('is_hidden',false)
               ->where('status', 'active')
               ->oldest('sorting')
               ->select('id', 'parent_id', 'name')
               ->whereNull('parent_id')
               ->get();
     }

     public function findAllHelpdeskById($companyId, $id)
     {
          return $this->helpdesk::query()
               ->whereIn('id', $id)
               ->where('company_id', $companyId)
               ->where('is_kontakkami', false)
               ->where('status', 'active')
               ->oldest('sorting')
               ->select('id', 'parent_id', 'name')
               ->get();
     }

     public function findHelpdeskById($companyId, $id)
     {
          return $this->helpdesk::query()
               ->where('company_id', $companyId)
               ->where('id', $id)
               ->firstOrFail();
     }
     public function findProductById($companyId, $productId)
     {
          return $this->product::query()
               ->with([
                    'helpdesk' =>
                         function ($query) {
                              $query->join('company_helpdesk_categories', 'company_helpdesk_categories.id', 'product_helpdesk.helpdesk_id');
                              $query->where('company_helpdesk_categories.status', 'active');
                              $query->select(['product_helpdesk.product_id', 'product_helpdesk.helpdesk_id']);
                         }
               ])
               ->where('is_kontakkami', false)
               ->where('company_id', $companyId)
               ->where('id', $productId)
               ->firstOrFail();
     }

     public function queryAvailableAgentByHelpdesk($companyId, $helpdeskId)
     {
          return $this->helpdeskUser::with(['user:id,name,username,profile,email'])
               ->join('company_users', function ($join) {
                    $join->on('company_users.user_id', 'user_helpdesk.user_id');
                    $join->on('company_users.company_id', 'user_helpdesk.company_id');
               })
               ->leftJoin('calls', function ($join) {
                    $join->on('calls.agent_id', 'company_users.user_id');
                    $join->on('calls.company_id', 'company_users.company_id');
                    $join->where('calls.status', 'active');
               })
               ->leftJoin('chat_users', function ($join) {
                    $join->on('chat_users.user_id', 'company_users.user_id');
                    $join->on('chat_users.company_id', 'company_users.company_id');
               })
               ->leftJoin('chats', function ($join) {
                    $join->on('chat_users.chat_id', 'chats.id');
                    $join->where('chats.status', 'active');
               })
               ->whereHas('user')
               ->where('user_helpdesk.role', 'agent')
               ->where('company_users.is_hide', 0)
               ->where('company_users.is_online', 1)
               ->where('company_users.status', 'active')
               ->where('company_users.activity', 'Online')
               ->whereNull('calls.id')
               ->whereNull('chats.id')
               ->where('company_users.company_id', $companyId)
               ->whereIn('user_helpdesk.helpdesk_id', $helpdeskId)
               ->select(['user_helpdesk.user_id', 'company_users.profile', 'company_users.name'])
               ->distinct();
     }

     public function findRandomAvailableAgent($companyId, $helpdeskId)
     {
          return $this->queryAvailableAgentByHelpdesk($companyId, $helpdeskId)
               ->inRandomOrder()
               ->first();
     }

     public function findAllAgentByHelpdesk($companyId, $helpdeskId)
     {
          return $this->queryAvailableAgentByHelpdesk($companyId, $helpdeskId)
               ->get()
               ->each(function ($row) {
                    $row->user->profile = asset($row->profile ?: $row->user->profile);
                    $row->user->name = $row->name ?: $row->user->name;
               });
     }

     public function findAllHelpdeskIdCompany($companyId, $helpdeskId)
     {
          return $this->helpdesk::where('company_id', $companyId)
               ->whereIn('id', $helpdeskId)
               ->pluck('id');
     }


     public function isHelpdeskAvailable($officeHour, $billing, $companyId)
     {
          if (!$billing) {
               return false;
          }
          $minimumBalance = @$billing->summary['additional']['additional_in_app']['minimum'];
          $minimumBalance = $minimumBalance ?: intval(Setting::findValue('minimum_balance') ?: 10000);
          $totalOnlineAccount = $billing->agent_spv_limit + $billing->comm_agent_limit; // + $billing->qc_am_escalation_limit + $billing->comm_agent_limit;
          $minimumBilling = $minimumBalance * $totalOnlineAccount;
          if (!$billing || $billing?->balance <= $minimumBilling) {
               return false;
          }

          $companyProfile = CompanyProfile::where('company_id', $companyId)->select('office_hour_type')->first();
          if (!$companyProfile)
               return false;

          if ($companyProfile->office_hour_type == 'custom') {
               if (!$officeHour)
                    return false;

               $currentDay = strtolower(date('l'));
               $currentTime = date('H:i');
               $officeTime = $officeHour->{$currentDay};

               if (!$officeTime)
                    return false;

               $startHour = $officeTime->start ?? '00:00';
               $endHour = $officeTime->end ?? '23:59';
               if ($currentTime < $startHour || $startHour > $endHour || $currentTime > $endHour) {
                    return false;
               }
          }

          return true;
     }
}
