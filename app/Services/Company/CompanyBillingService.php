<?php
namespace App\Services\Company;

use App\Models\Company\CompanyBilling;

class CompanyBillingService
{
     public function __construct(
          public $model = CompanyBilling::class
     ) {
     }

     public function findActiveBilling($companyId)
     {
          return $this->model::where('company_id', $companyId)
               ->where('status', 'active')
               ->where('category', 'current')
               ->orderBy('status', 'asc')
               ->select([
                    'plan_name',
                    'plan_type',
                    'expired_at',
                    'call_cost',
                    'balance_usage',
                    'balance',
                    'status',
                    'id',
                    'summary',
                    'additional',
                    'agent_spv_limit',
                    'comm_agent_limit'
               ])
               ->first();
     }
}