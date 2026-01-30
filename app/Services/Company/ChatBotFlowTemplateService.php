<?php
namespace App\Services\Company;

use App\Models\Data\ChatBotFlowTemplate;

class ChatBotFlowTemplateService
{
     public function __construct(
          public $model = ChatBotFlowTemplate::class
     ) {
     }

     public function findFlow($companyId, $category = 'in-app')
     {
          return $this->model::query()
               ->where('company_id', $companyId)
               ->where('category', $category)
               ->where('status', 'active')
               ->select(['flow', 'rejection_message', 'name','id','another_flows'])
               ->latest()
               ->first();
     }

     public function hasFlow($companyId, $category = 'in-app')
     {
          return $this->model::query()
               ->where('company_id', $companyId)
               ->where('category', $category)
               ->where('status', 'active')
               ->count();
     }
}