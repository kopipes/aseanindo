<?php

namespace App\Services\Company;

use App\Helpers\Yellow;
use App\Models\Company\Company;
use App\Models\Data\User;
use App\Services\Util\BlockedAccountService;
use Illuminate\Support\Facades\DB;

class CompanyService
{
     public function __construct(
          public $model = Company::class,
          private $blockedAccountService = new BlockedAccountService
     ) {
     }

     public function findAllUsernameCompany($search, $userId = null)
     {
          return $this->model::when($userId, function ($query) use ($userId) {
               $query->whereNotIn('id', $this->blockedAccountService->findAllBlockedId($userId, 'ba'));
          })
               ->when($search, function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                    $query->orWhere('username', 'like', "%{$search}%");
               })
               ->get([
                    'id',
                    'name',
                    'logo as picture',
                    'username',
                    DB::raw("'ba' as type")
               ]);
     }
     public function findByUsername($username)
     {
          $company = $this->model::with(['profile:company_id,address,brand_name'])
               ->select(['name', 'logo', 'username', 'id'])
               ->where('username', $username)
               ->where('status', 'approved')
               ->firstOrFail();
          $company->logo = asset($company->logo);
          return $company;
     }

     public function findProfileValue($companyId,$key){
          $data =  DB::table('company_profile')
          ->where('company_id',$companyId)
          ->select($key)
          ->first();
          return $data?->{$key} || null;
     }

     public function findById($id)
     {
          return $this->model::query()
               ->where('id', $id)
               ->first();
     }

     public function findDetailCompany($companyId)
     {
          $company = $this->model::join('company_profile as detail', 'detail.company_id', 'companies.id')
               ->where('companies.id', $companyId)
               ->where('companies.status', 'approved')
               ->select([
                    'companies.id',
                    'companies.name',
                    'companies.email',
                    'companies.logo as profile',
                    'detail.address',
                    'detail.latitude as lat',
                    'detail.longitude as lng',
                    'companies.created_at as join_date',
                    'detail.office_phone as phone',
                    'detail.description',
                    'detail.facebook',
                    'detail.twitter',
                    'detail.instagram',
                    'detail.youtube',
                    'companies.username'
               ])
               ->withTotalFollower()
               ->withTotalRating()
               ->firstOrFail();
          $company->follower = $company->follower ?: 0;
          $company->rating = $company->rating ? round($company->rating, 2) : 5;
          $company->join_date = date('d M, Y', strtotime($company->join_date));
          $company->profile = asset($company->profile);
          $company->is_block = false;
          $company->is_followed = false;
          $company->company_link = Yellow::shareLink('company', $company->id);

          return $company;
     }

     public function findAllUserAccountCompanyAndCustomer($userId, $latitude, $longitude, $filter, $search, $limit, $page = 1)
     {
          $category_id = @$filter['category_id'];
          $offset = ($page - 1) * $limit;

          $companyQuery = $this->model::query()
               ->join('company_profile', 'company_profile.company_id', 'companies.id')
               ->where('companies.status', 'approved')
               ->whereNotNull('companies.company_category_id')
               ->when($userId, function ($query) use ($userId) {
                    $query->whereNotIn('companies.id', $this->blockedAccountService->findAllBlockedId($userId, 'ba'));
               })
               ->when($search, fn($searchQuery) => $searchQuery->where(function ($query) use ($search) {
                    $query->where('companies.name', 'like', "%{$search}%");
                    $query->orWhere('company_profile.address', 'like', "%{$search}%");
                    $query->orWhere('companies.username', 'like', "%{$search}%");
               }))

               ->when($category_id, function ($query) use ($category_id) {
                    $query->whereIn('companies.company_category_id', $category_id);
               })
               ->select([
                    'companies.id',
                    'companies.name',
                    'companies.logo as profile',
                    DB::raw("'company' as type"),
                    'company_profile.address as info',
                    'company_profile.latitude as lat',
                    'company_profile.longitude as lng',
                    DB::raw("true as is_verified,companies.username,(3959 * acos(cos(radians('{$latitude}')) * cos(radians(latitude)) * cos( radians(longitude) - radians('{$longitude}')) + sin(radians('{$latitude}')) * sin(radians(latitude)))) AS distance")
               ]);


          if (!$category_id) {
               $customerQuery = User::join('customer_details as detail', 'detail.user_id', 'users.id')
                    ->where('users.role', 'customer')
                    ->whereNull('users.deleted_at')
                    ->where('detail.hide_search', 0)
                    ->when($userId, function ($query) use ($userId) {
                         $query->whereNotIn('users.id', $this->blockedAccountService->findAllBlockedId($userId, 'customer'));
                    })
                    ->when($search, fn($searchQuery) => $searchQuery->where(function ($query) use ($search) {
                         $query->where('users.name', 'like', "%{$search}%");
                         $query->orWhere('users.username', 'like', "%{$search}%");
                    }))
                    ->select([
                         'users.id',
                         'users.name',
                         'users.profile',
                         DB::raw("'customer' as type,null as info,null as lat,null as lng,false as is_verified,users.username,99999999999 as distance"),
                    ]);
               $finalQuery = $companyQuery->union($customerQuery);
          } else {
               $finalQuery = $companyQuery;
          }

          return $finalQuery
               ->offset($offset)
               ->limit($limit)
               ->orderBy('distance', 'asc')
               ->get()
               ->each(fn($row) => $row->distance = round($row->distance, 2));
     }

     public function findSpvAgent($agentId, $companyId, $userCategory)
     {
          $table = "view_inbound_teams";
          if ($userCategory == 'escalation') {
               $table = "view_escalation_user_team";
          }
          return DB::table($table)
               ->where('company_id', $companyId)
               ->where('user_id', $agentId)
               ->whereIn('team_role', ['spv', 'spv_escalation'])
               ->first()?->team_id ?: null;
     }
}