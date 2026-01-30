<?php

namespace App\Http\Controllers\CallnChat\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Company\CompanyProfile;
use App\Models\Util\InboundMessageTemplate;
use App\Services\Company\CompanyFaqService;
use App\Services\Company\OfficeHourService;
use App\Services\Company\CompanyUserService;
use App\Services\Company\CompanyBillingService;
use App\Services\Company\CompanyHelpdeskService;

class HelpdeskController extends Controller
{
    public function __construct(
        private CompanyHelpdeskService $companyHelpdeskService,
        private CompanyUserService $companyUserService,
        private OfficeHourService $officeHourService,
        private CompanyBillingService $companyBillingService,
        private CompanyFaqService $companyFaqService
    ) {
    }
    public function product(Request $request)
    {
        return $this->companyHelpdeskService->findAllProduct($request->companyId);
    }

    public function topFaq(Request $request){
        return $this->companyFaqService->findTopTen($request->companyId);
    }


    public function faq(Request $request){
        return $this->companyFaqService->findAll($request->companyId);
    }

    public function detailFaq(Request $request,$faqUuid){
        return $this->companyFaqService->detailFaq($request->companyId,$faqUuid);
    }
    public function helpdeskCategory(Request $request)
    {
        $id = $request->get('id',[]);
        if($id){
            return $this->companyHelpdeskService->findAllHelpdeskById($request->companyId,$id);
        }
        return $this->companyHelpdeskService->findAllHelpdesk($request->companyId);
    }

    public function agent(Request $request, $id, $category)
    {
        $name = null;
        $helpdeskId = [$id];
        $companyId = $request->companyId;
        $officeHour = $this->officeHourService->findCompanyOfficeHour($companyId);


        if ($category === 'product') {
            $product = $this->companyHelpdeskService->findProductById($companyId, $id);
            $name = $product->name;
            $helpdeskId = $product->helpdesk->pluck('helpdesk_id')->toArray();
        } else {
            $name = $this->companyHelpdeskService->findHelpdeskById($companyId, $id)->name;
        }
        $billing = $this->companyBillingService->findActiveBilling($companyId);
        $isHelpdeskAvailable = $this->companyHelpdeskService->isHelpdeskAvailable($officeHour, $billing, $companyId);
        $helpdeskId = $isHelpdeskAvailable ? $helpdeskId : [];

        // Check if BA buy social media
        $whatsAppUrl = null;
        $emailUrl = null;
        $singleAvatar = false;
        $agentList = [];
        if ($isHelpdeskAvailable) {
            $hasMainSosmed = @$billing->summary['additional']['social_media']['selected'];
            $hasAdditionalSosmed = collect($billing->additional)->where('items.additional.social_media.selected', true)->count();
            
            $hasMainEmail = @$billing->summary['additional']['email']['selected'];
            $hasAdditionalEmail = collect($billing->additional)->where('items.additional.email.selected', true)->count();

            $agentList = $this->companyHelpdeskService->findAllAgentByHelpdesk($companyId, $helpdeskId);
            
            $companyProfile = CompanyProfile::query()
                ->where('company_id', $companyId)
                ->select(['brand_name','single_avatar'])
                ->first();

            if($companyProfile?->single_avatar && count($agentList)){
                $agentList = [];
                $singleAvatar = true;
            }

            if ($hasMainSosmed ?: $hasAdditionalSosmed) {
                $whatsappAccount = DB::table('company_whatsapp_accounts')
                    ->where('company_id', $companyId)
                    ->where('status', 'active')
                    ->select(['whatsapp_number'])
                    ->first();
                if ($whatsappAccount) {
                    $textMessage = "Halo, saya ingin bertanya tentang product {$companyProfile->brand_name}";
                    $whatsAppUrl = "https://api.whatsapp.com/send?phone={$whatsappAccount->whatsapp_number}&text={$textMessage}";
                }
            }

            if ($hasMainEmail ?: $hasAdditionalEmail) {
                $emailAccount = DB::table('company_email_accounts')
                    ->where('company_id', $companyId)
                    ->where('status', 'active')
                    ->select(['username'])
                    ->first();
                if ($emailAccount) {
                    $emailUrl = "mailto:{$emailAccount->username}";
                }
            }
        }
        
        return [
            "name" => $name,
            "agent" => $agentList,
            "is_available" => ((count($helpdeskId) && ($whatsAppUrl || $emailUrl)) || $singleAvatar) ? true : false,
            "office_hours" => $officeHour,
            "whatsAppUrl" => $whatsAppUrl,
            "emailUrl" => $emailUrl,
            "singleAvatar" => $singleAvatar,
            "template" => [
                "end_chat" => InboundMessageTemplate::endChat($companyId)
            ]
        ];
    }

    public function availableAgent(Request $request,$id,$category){
        $companyId = $request->companyId;

        $helpdeskId = [$id];
        if ($category === 'product') {
            $product = $this->companyHelpdeskService->findProductById($companyId, $id);
            $helpdeskId = $product->helpdesk->pluck('helpdesk_id')->toArray();
        }
        $agent = $this->companyHelpdeskService->findRandomAvailableAgent($companyId, $helpdeskId);
        return $agent;
    }

    public function helpdeskList(Request $request, $id, $category)
    {
        $helpdeskId = [$id];
        $companyId = $request->companyId;
        if ($category === 'product') {
            $product = $this->companyHelpdeskService->findProductById($companyId, $id);
            $helpdeskId = $product->helpdesk->pluck('helpdesk_id')->toArray();
        }
        return $helpdeskId;
    }

    public function detailAgent(Request $request, $id)
    {
        $companyId = $request->companyId;
        $agent = $this->companyUserService->findActiveAgentById($companyId, $id);
        if ($agent)
            unset($agent->user->regid);
        return [
            "agent" => $agent,
            "template" => [
                "end_chat" => InboundMessageTemplate::endChat($companyId)
            ]
        ];
    }
}
