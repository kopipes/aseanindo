<?php

namespace App\Services\Util;

use App\Models\Util\Following;
use App\Services\Util\BlockedAccountService;

class FollowingService
{
     public function __construct(
          public $model = Following::class,
          private $blockedAccountService = new BlockedAccountService
     ) {
     }

     public function followBa($customerId, $companyId)
     {
          $properties = [
               'company_id' => $companyId,
               'user_id' => $customerId,
          ];
          return $this->model::updateOrCreate($properties, $properties);
     }

     public function unFollowBa($customerId, $companyId)
     {
          return $this->model::where('company_id', $companyId)
               ->where('user_id', $customerId)
               ->delete();
     }
     public function findAllFollowedId($customerId)
     {
          return $this->model::where('user_id', $customerId)
               ->distinct()
               ->pluck('company_id')
               ->toArray();
     }
     public function isAccountFollowed($companyId, $userId)
     {
          return $this->model::where('user_id', $userId)
               ->where('company_id', $companyId)
               ->select('created_at')
               ->first();
     }

     public function findAllFollowerCompany($companyId, $limit = 10)
     {
          return $this->model::join('users', 'users.id', 'followings.user_id')
               ->where('followings.company_id', $companyId)
               ->where('users.role', 'customer')
               ->select([
                    'users.id', 'users.name', 'users.profile', 'users.email', 'users.phone', 'users.username'
               ])
               ->paginate($limit ?: 10)
               ->each(fn ($user) => $user->profile = asset($user->profile));
     }

     public function findAllFollowedCustomer($customerId, $userLoginId, $limit = 10)
     {
          $userBlockedCompanies = collect([]);
          $userFollowingCompanies = collect([]);
          if ($userLoginId) {
               $userBlockedCompanies = $this->blockedAccountService->findAllBlockedId($userLoginId, 'ba');
               $userFollowingCompanies = $this->findAllFollowedId($userLoginId);
          }

          return $this->model::join('companies', 'companies.id', 'followings.company_id')
               ->where('followings.user_id', $customerId)
               ->select([
                    'companies.id', 'companies.name', 'companies.logo as profile', 'companies.username'
               ])
               ->paginate($limit ?: 10)
               ->each(function ($companyId) use ($userBlockedCompanies, $userFollowingCompanies) {
                    $companyId->profile = asset($companyId->profile);
                    $companyId->is_block =  in_array($companyId->id, $userBlockedCompanies);
                    $companyId->is_followed = in_array($companyId->id, $userFollowingCompanies);
               });
     }
}
