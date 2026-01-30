<?php

namespace App\Http\Controllers\CallnChat;

use App\Services\Company\CompanyService;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use App\Services\Company\ChatBotFlowTemplateService;
use App\Services\Company\CompanyBillingService;

class CallnChatIndexController extends Controller
{
    public function __construct(
        private CompanyService $companyService,
        private ChatBotFlowTemplateService $chatBotFlowTemplateService,
        private CompanyBillingService $companyBillingService
    ) {
    }
    public function __invoke(Request $request, $username)
    {
        $hasChatBot = false;
        $hasFaq = false;
        $company = $this->companyService->findByUsername($username);
        $enableCall = $this->companyService->findProfileValue($company->id,'enable_embed_call');
        $hasChatBotFlow = $this->chatBotFlowTemplateService->hasFlow($company->id);
        $billing = $this->companyBillingService->findActiveBilling($company->id);
        if($billing){
            $summary = $billing->summary;
            $additional = $billing->additional;

            $hasChatBot = @$summary['additional']['chatbot']['selected'];
            $additionalHasChatBot = collect($additional)->where('items.additional.chatbot.selected', true)->count();
            $hasChatBot = $hasChatBot ?: $additionalHasChatBot;

            $hasFaq = @$summary['additional']['faq']['selected'];
            $additionalHasFaq = collect($additional)->where('items.additional.faq.selected', true)->count();
            $hasFaq = $hasFaq ?: $additionalHasFaq;
        }

        return view('callnchat.app', [
            'page' => [
                'company' => $company,
                'environment' => config('app.env'),
                'base_url' => url(''),
                'term_condition' => config('services.term_condition'),
                'privacy_policy' => config('services.privacy_policy'),
                'enable_call' => $enableCall,
                'has_chatbot_flow' => $hasChatBotFlow,
                'has_chatbot' => $hasChatBot,
                'has_faq' => $hasFaq,
                'app_url' => url($username)
            ]
        ]);
    }
}