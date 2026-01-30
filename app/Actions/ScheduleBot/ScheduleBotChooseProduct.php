<?php
namespace App\Actions\ScheduleBot;

use App\Models\Data\TicketScheduleDetail;
use Illuminate\Http\Request;
use App\Models\Data\CompanyProduct;
use App\Services\CallnChat\ChatBotService;
use App\Actions\ChatBot\ResponseBotMessage;

class ScheduleBotChooseProduct
{
     public function __construct(
          private $chatBotService = new ChatBotService
     ) {
     }
     
     public function handle(Request $request, $companyId)
     {
          $product_id = $request->product_id;

          $product = CompanyProduct::query()
               ->with(['detail:product_id,location,start_date,end_date,start_time,end_time,max_booking'])
               ->where('id', $product_id)
               ->where('company_id', $companyId)
               ->whereNotNull('chatbot_forms')
               ->select(['id', 'name', 'image', 'description', 'category', 'chatbot_forms', 'closing_statement', 'verification_email'])
               ->firstOrFail();

          $hasSubject = false;
          
          $chatBotForm = collect($product->chatbot_forms)->sortBy([
               ['group_sorting', 'asc'],
               ['sorting', 'asc']
          ])
               ->groupBy('group_name')
               ->map(function ($forms, $group) {
                    return [
                         "label" => $group,
                         "type" => "group",
                         "items" => collect($forms)->map(function ($item) {
                              return [
                                   'label' => $item['label'],
                                   'type' => $item['type'],
                                   'slug' => $item['slug'],
                                   'value' => '',
                                   'options' => $item['options'],
                                   'is_default' => false
                              ];
                         })
                    ];
                    ;
               })
               ->values();

          $bookeds = [];
          if($product->category=='schedule_other'){
               $date = $request->date;
               $bookeds = TicketScheduleDetail::query()
                    ->where('company_id',$companyId)
                    ->where('product_id',$product_id)
                    ->where('counseling_date',$date)
                    ->select(['number','counseling_time'])
                    ->get();
              
          }
          $forms =[
               [
                    "label" => "General",
                    "type" => "group",
                    "items" => [
                         [
                              'label' => $product->category=='schedule_other' ? 'Customer Name'  :'Fullname',
                              'type' => 'text',
                              'slug' => 'name',
                              'value' => '',
                              'options' => [],
                              'is_default' => true
                         ],
                         [
                              'label' => 'Email Address',
                              'type' => 'email',
                              'slug' => 'email',
                              'value' => '',
                              'options' => [],
                              'is_default' => true
                         ],
                         [
                              'label' => 'Phone Number',
                              'type' => 'phone_number',
                              'slug' => 'phone_number',
                              'code' => '62',
                              'value' => '',
                              'options' => [],
                              'is_default' => true
                         ]
                    ]
               ],

          ];


          $subjects = (new ResponseBotMessage())->findAllProductSubject($product->id);
          $subjectList = collect($subjects)->map(function ($subject) {
               return [
                    'value' => $subject->id,
                    'label' => $subject->name,
                    'subs' => collect($subject->subs)->map(function ($row) {
                         return [
                              'value' => $row->id,
                              'label' => $row->name,
                              'childs' => collect($row->child)->map(function ($child) {
                                   return [
                                        'value' => $child->id,
                                        'label' => $child->name,
                                   ];
                              })
                         ];
                    })
               ];
          });
          if (count($subjectList)) {
               $forms = [
                    ...$forms,
                    [
                         'label' => 'Choose Subject',
                         'group' => null,
                         'type' => 'subject',
                         'slug' => 'subject_id',
                         'value' => '',
                         'options' => $subjectList,
                         'help' => ''
                    ],
               ];
               $hasSubject = true;
          }
          return [
               'closing_statement' => $product->closing_statement ?: 'Terimakasih',
               'verification_email' => $product->verification_email,
               'bookeds' => $bookeds,
               'hasSubject' => $hasSubject,
               'forms' => [
                    ...$forms,
                    ...$chatBotForm
               ]
          ];
     }
}