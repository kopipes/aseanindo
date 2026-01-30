<?php
namespace App\Services\CallnChat;

use App\Helpers\Yellow;
use App\Models\Chat\ChatBot;
use App\Models\Chat\ChatBotMessage;
use App\Models\Data\CompanyProduct;
use App\Services\Company\ChatBotFlowTemplateService;

class ChatBotService
{
     public function __construct(
          public $model = ChatBot::class,
          public $message = ChatBotMessage::class,
          private $companyProduct = CompanyProduct::class,
          private $chatBotFlowTemplateService = new ChatBotFlowTemplateService
     ) {
     }


     public function createMessage($id, $sender, $message, $type = 'message')
     {
          return $this->message::create([
               'chatbot_id' => $id,
               'sender' => $sender,
               'message' => json_encode($message),
               'message_type' => $type,
          ]);
     }


     public function findById($id, $select = ['*'])
     {
          return $this->model::query()
               ->where('id', $id)
               ->select($select)
               ->firstOrFail();
     }

     public function findAllConversation($id)
     {
          $chatBot = $this->findById($id, ['product_form_scene', 'form_complete']);
          $productFormScene = $chatBot->product_form_scene ?: [];
          $lastOption = count($productFormScene) == 1;

          return $this->message::query()
               ->select(['sender', 'message', 'message_type', 'created_at'])
               ->where('chatbot_id', $id)
               ->orderBy('created_at', 'asc')
               ->get()
               ->each(function ($row) use ($lastOption, $chatBot) {
                    if($row->sender=='bot'){
                         if(isset($row->message?->message)){
                              $row->message->message = Yellow::convertTextToHtml($row->message->message);
                         }
                    }
                    $row->lastOption = $lastOption;
                    $row->complete_booking = boolval($chatBot->form_complete);
               });
     }

     public function handleMessageOption($message)
     {
          if ($message) {
               if ($message['type'] == 'message_option') {
                    $message['options'] = collect($message['options'])->map(function ($row) {
                         unset($row['next']);
                         return $row;
                    })->values()->toArray();
               }
               $message['message'] = Yellow::convertTextToHtml($message['message']);
               return $message;
          }
          return null;
     }

     public function findAllProductFlow($botId)
     {
          $session = $this->findById($botId);
          $scene = $session->current_scene;
          if (!$scene == 'message_data_request') {
               return [];
          }

          $currentDate = date('Y-m-d H:i:s');
          $products_id = @$scene['properties']['products_id'] ?: [];
          return $this->companyProduct::query()
               ->with([
                    'detail:product_id,location,start_date,end_date,start_time,end_time,max_booking',
                    'bookings'
               ])
               ->whereIn('id', $products_id)
               ->where('company_id', $session->company_id)
               ->whereNotNull('chatbot_forms')
               ->select(['id', 'name', 'image', 'description', 'form_template_id', 'category'])
               ->latest()
               ->get()
               ->each(function ($row) {
                    $row->max_booking = 1;
                    if ($row->detail) {
                         $row->start_date = date('Y-m-d', strtotime($row->detail->start_date)) . ' ' . date('H:i:s', strtotime($row->start_time));
                         $row->end_date = date('Y-m-d', strtotime($row->detail->end_date)) . ' ' . date('H:i:s', strtotime($row->end_time));
                         $row->detail->start_date = date('d M Y', strtotime($row->detail->start_date));
                         $row->detail->end_date = date('d M Y', strtotime($row->detail->end_date));
                         $row->max_booking = $row->detail?->max_booking ?: 0;
                    }
                    $row->image = asset($row->image ?: config('services.placeholder_avatar'));
                    if ($row->category == 'general') {
                         $row->start_date = now()->addMonths(-1)->format('Y-m-d H:i:s');
                         $row->end_date = now()->addMonth()->format('Y-m-d H:i:s');
                         $row->bookings_count = 0;
                    } else {
                         $row->bookings_count = $row->bookings->sum('number');
                    }
                    unset($row->bookings);
               })
               ->where('start_date', '<=', $currentDate)
               ->where('end_date', '>=', $currentDate)
               ->filter(fn($row) => $row->max_booking > $row->bookings_count)
               ->values();

     }
}