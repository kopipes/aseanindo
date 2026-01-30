<?php

namespace App\Services\User;

use App\Models\Data\User;
use App\Services\Util\BlockedAccountService;
use Illuminate\Support\Facades\DB;

class UserService
{
     public function __construct(
          public $model = User::class,
          private $blockedAccountService = new BlockedAccountService
     ) {
     }

     public function findAllUsernameCompany($search, $userId = null)
     {
          return $this->model::when($userId, function ($query) use ($userId) {
               $query->whereNotIn('id', $this->blockedAccountService->findAllBlockedId($userId, 'customer'));
          })
               ->when($search, function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                    $query->orWhere('username', 'like', "%{$search}%");
               })
               ->get([
                    'id',
                    'name',
                    'profile as picture',
                    'username',
                    DB::raw("'customer' as type")
               ]);
     }
     public function findCustomerProfile($id)
     {
          return $this->model::leftJoin('customer_details as detail', 'detail.user_id', 'users.id')
               ->where('users.id', $id)
               ->where('users.role', 'customer')
               ->select([
                    'users.name',
                    'users.email',
                    'users.phone',
                    'users.profile',
                    'users.lang',
                    'users.created_at',
                    'detail.bio',
                    'detail.facebook',
                    'detail.twitter',
                    'detail.instagram',
                    'detail.hide_search',
                    'users.username'
               ])
               ->withCustomerFollower()
               ->firstOrFail();
     }

     public function findCustomer($id)
     {
          return $this->model::where('id', $id)
               ->where('role', 'customer')
               ->first();
     }


     public function findAgent($id)
     {
          return $this->model::where('id', $id)
               ->where('role', 'agent')
               ->first();
     }


     public function findSpvCompany($id, $companyId)
     {
          return $this->model::leftJoin('company_users', 'company_users.user_id', 'users.id')
               ->where('users.id', $id)
               ->where('company_users.company_id', $companyId)
               ->whereIn('users.role', ['spv','spv_escalation','am'])
               ->select([
                    'users.id',
                    'users.name',
                    'company_users.profile'
               ])
               ->first();
     }

     public function findCustomerByEmail($email, $select = ['*'])
     {
          return $this->model::where('email', $email)
               ->where('role', 'customer')
               ->select($select)
               ->first();
     }

     public function findCustomerByEmailOrPhoneWithTrashed($email, $phone)
     {
          return $this->model::where('role', 'customer')
               ->where(function ($query) use ($email, $phone) {
                    $query->where('email', $email);
                    $query->orWhere('phone', $phone);
               })
               ->withTrashed()
               ->select(['id', 'email', 'phone', 'deleted_at', 'name', 'profile'])
               ->first();
     }

     public function updateOrCreate($id, $properties)
     {
          return $this->model::withTrashed()
               ->updateOrCreate(['id' => $id], $properties);
     }


     public function findDetailCustomer($customerId)
     {
          $customer = $this->model::leftJoin('customer_details as detail', 'detail.user_id', 'users.id')
               ->where('users.role', 'customer')
               ->where('users.id', $customerId)
               ->select([
                    'users.id',
                    'users.name',
                    'users.profile',
                    'detail.bio',
                    'users.created_at as join_date',
                    'users.username',
                    'detail.facebook',
                    'detail.twitter',
                    'detail.instagram'
               ])
               ->withCustomerFollower()
               ->firstOrFail();

          $customer->join_date = date('d M, Y', strtotime($customer->join_date));
          $customer->following = $customer->following ?: 0;
          $customer->is_block = false;
          return $customer;
     }
}