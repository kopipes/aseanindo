<?php

namespace App\Services\Util;

use App\Models\Util\ReportSpam;

class ReportSpamService
{
     public function __construct(
          public $model = ReportSpam::class
     ) {
     }

     public function reportContent($accountId, $accountType, $reportBy, $reason)
     {
          $properties = [];
          switch ($accountType) {
               case 'company':
                    $properties = [
                         'company_id' => $accountId,
                         'type' => 'ba'
                    ];
                    break;
               case 'customer':
                    $properties = [
                         'customer_id' => $accountId,
                         'type' => 'customer'
                    ];
                    break;
               case 'product':
                    $properties = [
                         'product_id' => $accountId,
                         'type' => 'product'
                    ];
                    break;
          }
          
          return $this->model::create([
               'report_by' => $reportBy,
               'reason' => $reason,
               ...$properties
          ]);
     }

     public function findReport($accountId, $accountType, $reportBy)
     {
          $properties = [];
          switch ($accountType) {
               case 'company':
                    $properties = [
                         ['company_id', $accountId],
                         ['type', 'ba'],
                         ['report_by', $reportBy]
                    ];
                    break;
               case 'customer':
                    $properties = [
                         ['customer_id', $accountId],
                         ['type', 'customer'],
                         ['report_by', $reportBy]
                    ];
                    break;
               case 'product':
                    $properties = [
                         ['product_id', $accountId],
                         ['type', 'product'],
                         ['report_by', $reportBy]
                    ];
                    break;
          }

          return $this->model::where($properties)->first();
     }
}
