<?php

namespace App\Actions\Call;

use App\Helpers\Yellow;
use App\Models\Call\CallbackCallRequest;
use App\Models\Company\Company;
use App\Models\Data\CompanyContactCustomer;
use App\Models\Data\Notification;
use App\Services\Company\CompanyHelpdeskService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequestCallCallback
{
     public function __construct(
          private $companyHelpdeskService = new CompanyHelpdeskService
     ) {
     }
     public function handle(Request $request, $companyId, $userId)
     {

          $userId = $request->user_id;
          $company = Company::where('id', $companyId)->select(['name'])->firstOrFail();

          $items = $this->companyHelpdeskService->findAllHelpdeskIdCompany($companyId, $request->helpdesk_category_id)
               ->map(fn($id) => [
                    'id' => Str::uuid(),
                    'created_at' => now(),
                    'company_id' => $companyId,
                    'customer_id' => $userId,
                    'company_helpdesk_id' => $id,
                    // 'product_id' => $request->product_id,
                    'note' => $request->note
               ])->toArray();
          CallbackCallRequest::insert($items);
          
          $phoneCode = $request->phone_code ?: '62';
          CompanyContactCustomer::updateOrCreate([
               'company_id' => $companyId,
               'customer_id' => $userId
          ], [
               'customer_id' => $userId,
               'company_id' => $companyId,
               'name' => $request->name,
               'email' => $request->email,
               'phone_code' => $phoneCode,
               'phone' => Yellow::phoneNumber($request->phone,$phoneCode),
          ]);

          Notification::create([
               'company_id' => $companyId,
               'user_id' => $userId,
               'user_role' => 'customer',
               'category' => 'call_history',
               'title' => 'Callback',
               'description' => "Anda akan segera di hubungi oleh Help Desk {$company->name}",
               'type' => 'Callback',
          ]);
     }
}
