<?php

namespace App\Services\Util;

use App\Models\Util\BlockedAccount;

class BlockedAccountService
{
     public function __construct(
          public $model = BlockedAccount::class
     ) {
     }

     public function findAllBlockedId($blockedBy, $blockedType)
     {
          return $this->model::where('blocked_type', $blockedType)
               ->where('blocked_by', $blockedBy)
               ->distinct()
               ->pluck('blocked_id')
               ->toArray();
     }

     public function isAccountBlocked($blockedId, $blockedById, $blockedType)
     {
          return $this->model::where('blocked_type', $blockedType)
               ->where('blocked_by', $blockedById)
               ->where('blocked_id', $blockedId)
               ->select('created_at')
               ->first();
     }

     public function blockAccount($blockedBy, $blockedId, $type)
     {
          $type = $type === 'company' ? 'ba' : 'customer';
          $properties = [
               'blocked_by' => $blockedBy,
               'blocked_id' => $blockedId,
               'blocked_type' => $type
          ];
          return $this->model::updateOrCreate($properties, $properties);
     }

     public function unBlockAccount($blockedBy, $blockedId, $type)
     {
          $type = $type === 'company' ? 'ba' : 'customer';
          return $this->model::query()
               ->where('blocked_by', $blockedBy)
               ->where('blocked_id', $blockedId)
               ->where('blocked_type', $type)
               ->delete();
     }

     public function findAllBlockedCompany($blockedBy)
     {
          return $blockedBy
               ? $this->model::where('blocked_type', 'ba')
               ->where('blocked_by', $blockedBy)
               ->pluck('blocked_id')
               ->toArray()
               : [];
     }

     public function findAllUserBlockedAccount($blockedBy, $limit)
     {
          return $this->model::leftJoin('companies', 'companies.id', 'blocked_accounts.blocked_id')
               ->leftJoin('users', 'users.id', 'blocked_accounts.blocked_id')
               ->where('blocked_accounts.blocked_by', $blockedBy)
               ->select([
                    'blocked_id as id', 'blocked_accounts.created_at', 'blocked_type as type',
                    'users.name as customer_name', 'users.profile',
                    'companies.name as company_name', 'companies.logo',
               ])
               ->paginate($limit ?: 10);
     }
}
