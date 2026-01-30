<?php
namespace App\Services\Company;

use App\Models\Data\CompanyFaq;

class CompanyFaqService
{
     public function __construct(
          public $model = CompanyFaq::class
     ) {
     }

     public function findAll($companyId)
     {
          return $this->model::query()
               ->where('company_faqs.company_id', $companyId)
               ->whereNull('company_faqs.parent_id')
               ->select(['company_faqs.id', 'company_faqs.name', 'company_faqs.description'])
               ->withCount(["subs"])
               ->orderByRaw('company_faqs.sorting asc,company_faqs.created_at asc')
               ->get();
     }

     public function findTopTen($companyId)
     {
          return $this->model::query()
               ->where('company_faqs.company_id', $companyId)
               ->where('company_faqs.top_ten', true)
               ->select(['company_faqs.id', 'company_faqs.name', 'company_faqs.description'])
               ->orderByRaw('company_faqs.sorting asc,company_faqs.created_at asc')
               ->get();
     }

     public function detailFaq($companyId,$faqId)
     {
          return $this->model::query()
               ->leftJoin("company_products", function ($join) {
                    $join->on("company_products.faq_id", "company_faqs.id");
                    $join->on("company_products.company_id", "company_faqs.company_id");
               })
               ->with(['subs'])
               ->where('company_faqs.company_id', $companyId)
               ->where('company_faqs.id', $faqId)
               ->select(['company_faqs.id', 'company_faqs.name', 'company_faqs.description', 'company_products.id as product_id','company_faqs.parent_id'])
               ->first();
     }

}