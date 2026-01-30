<?php
namespace App\Actions\ChatBot;

use Illuminate\Http\Request;
use App\Models\Data\CompanyProduct;
use App\Services\CallnChat\ChatBotService;

class SendBotMessage
{
     public function __construct(
          private $chatBotService = new ChatBotService
     ) {
     }
     public function handle(Request $request, $botId)
     {
          $message = $request->message;
          $type = $request->type;
          $next = null;
          if ($type == 'product') {
               $chatBot = $this->chatBotService->findById($botId);
               $product = CompanyProduct::query()
                    ->with(['detail:product_id,location,start_date,end_date,start_time,end_time,max_booking'])
                    ->where('id', $message)
                    ->where('company_id', $chatBot->company_id)
                    ->select(['id', 'name', 'image', 'description', 'category', 'chatbot_forms'])
                    ->firstOrFail();
               $additionalMessage = "";
               if (in_array($product->category, ['schedule_professional', 'schedule_other'])) {
                    $additionalMessage .= "<br>";
                    $additionalMessage .= implode(',',$product->detail->location);
                    $additionalMessage .= " ".date('d M Y',strtotime($product->detail->start_date))." - ".date('d M Y',strtotime($product->detail->end_date));
                    $additionalMessage .= ", ".$product->detail->start_time." - ".$product->detail->end_time;
               }
               $this->chatBotService->message::create([
                    'chatbot_id' => $botId,
                    'sender' => 'customer',
                    'message' => "$product->name $additionalMessage",
                    'message_type' => 'message',
               ]);


               $this->insertProductMessage($next, $product, $chatBot,$additionalMessage);
               $type = 'message';
               $message = "$product->name $additionalMessage";

          } else {
               $this->chatBotService->message::create([
                    'chatbot_id' => $botId,
                    'sender' => 'customer',
                    'message' => $message,
                    'message_type' => $type,
               ]);
          }


          return [
               'message' => [
                    'message' => $message,
                    'message_type' => $type,
                    'sender' => 'customer',
                    'time' => date('H:i')
               ],
               'next' => $next,
               'type' => $type,
          ];
     }

     public function insertProductMessage(&$next, $product, $chatBot,$additionalMessage)
     {
          /**
           * Insert product message
           */
          $productMessage = [
               'image' => asset($product->image),
               'name' => $product->name.$additionalMessage,
               'description' => $product->description,
               'id' => $product->id
          ];

          $this->chatBotService->message::create([
               'chatbot_id' => $chatBot->id,
               'sender' => 'bot',
               'message' => json_encode($productMessage),
               'message_type' => 'bot_product',
          ]);

          $forms = collect($product->chatbot_forms)->map(function ($row) {
               $row['content'] = '';
               return $row;
          })->toArray();

          $chatBot->update([
               'product_form_scene' => collect($product->chatbot_forms)->sortBy([
                    ['group_sorting', 'asc'],
                    ['sorting', 'asc']
               ])->values()->toArray(),
               'customer_data' => [
                    'product_id' => $product->id,
                    'product_category' => $product->category,
                    'verification_email' => @$chatBot->current_scene['properties']['verification_email'] ? true : false,
                    'name' => null,
                    'email' => null,
                    'phone_code' => '62',
                    'phone_number' => null,
                    'subject_id' => null,
                    'quantity' => null,
                    'forms' => $forms,
               ],
               'form_complete' => 0
          ]);
          $next = [
               'message' => $productMessage,
               'message_type' => 'bot_product',
               'sender' => 'bot',
               'time' => date('H:i')
          ];
     }
}