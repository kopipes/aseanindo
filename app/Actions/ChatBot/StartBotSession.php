<?php
namespace App\Actions\ChatBot;

use App\Models\Company\CompanyBilling;
use App\Services\Company\CompanyBillingService;
use Illuminate\Http\Request;
use App\Services\CallnChat\ChatBotService;
use App\Services\Company\ChatBotFlowTemplateService;
use Illuminate\Support\Facades\DB;

class StartBotSession
{
     public function __construct(
          private $chatBotFlowTemplateService = new ChatBotFlowTemplateService,
          private $companyBillingService = new CompanyBillingService,
          private $chatBotService = new ChatBotService
     ) {
     }
     public function handle(Request $request, $companyId)
     {
          if(!$request->session){
               $this->handleBilling($companyId);
          }
          $chatBotTemplate = $this->chatBotFlowTemplateService->findFlow($companyId);
          $flow = $chatBotTemplate->flow;
          $nextFlow = $flow['next'];
          $currentFlow = $flow;
          unset($currentFlow['next']);

          $chatBot = $this->chatBotService->model::create([
               'company_id' => $companyId,
               'chatbot_flow_template_id' => $chatBotTemplate->id,
               'flow' => $chatBotTemplate->flow,
               'all_flows' => [
                    ...[
                         $chatBotTemplate->flow
                    ],
                    ...$chatBotTemplate->another_flows ?: []
               ],
               'current_scene' => $currentFlow,
               'next_scene' => $nextFlow,
               'rejection_message' => $chatBotTemplate->rejection_message,
               'current_step' => [
                    'index' => 1,
                    'type' => $currentFlow['type']
               ],
          ]);

          $this->chatBotService->message::create([
               'chatbot_id' => $chatBot->id,
               'sender' => 'bot',
               'message' => json_encode($currentFlow),
               'message_type' => "bot_" . $currentFlow['type'],
          ]);

          $message = $chatBot['current_scene'];

          return [
               'message' => [
                    'message' => $this->chatBotService->handleMessageOption($message),
                    'sender' => 'bot',
                    'message_type' => "bot_" . $message['type'],
                    'time' => date('H:i')
               ],
               'type' => $message['type'],
               'session_id' => $chatBot->id
          ];
     }

     public function handleBilling($companyId){
          $billing = $this->companyBillingService->findActiveBilling($companyId);
          if($billing){
               $botPrice = DB::table('cms_base_value')->where('key','bot_price_daily')->select(['value'])->first()?->value ?: 1000;
               $botPrice = intval($botPrice);
               $companyBalance = intval($billing->balance) - $botPrice;
               CompanyBilling::where('id',$billing->id)->update([
                    'balance_usage' => $billing->balance_usage + $botPrice,
                    'balance' => $companyBalance
               ]);
          }
     }
}