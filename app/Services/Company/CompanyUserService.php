<?php
namespace App\Services\Company;

use App\Models\Company\CompanyUser;

class CompanyUserService
{
     public function __construct(
          public $companyUser = CompanyUser::class
     ) {
     }


     public function findActiveAgentById($companyId, $agentId)
     {
          $user = $this->companyUser::with(['user:id,name,username,platform,regid'])
               ->where('company_id', $companyId)
               ->where('user_id', $agentId)
               ->where('role', 'agent')
               ->where('is_hide', 0)
               ->where('is_online', 1)
               ->where('status', 'active')
               ->select(['id', 'user_id', 'profile', 'activity','chat_id','call_id','name'])
               ->firstOrFail();
          $user->profile = asset($user->profile);
          $user->user->name = $user->name ?: $user->user->name;
          return $user;
     }

     public function updateActivityUser($companyId, $userId, $properties)
     {
          return $this->companyUser::where('company_id', $companyId)
               ->where('user_id', $userId)
               ->update($properties);
     }

     public function findCompanyUser($companyId, $userId)
     {
          return $this->companyUser::query()
               ->where('company_id', $companyId)
               ->where('user_id', $userId)
               ->first();
     }
}