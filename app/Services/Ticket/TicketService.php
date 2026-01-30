<?php

namespace App\Services\Ticket;

use App\Models\Data\Ticket;
use Illuminate\Support\Facades\DB;

class TicketService
{
     public function __construct(
          public $model = Ticket::class
     ) {
     }

     public function findAllTicketBySourceId($source, $sourceId)
     {
          return $this->model::query()
               ->leftJoin('ratings',function($join){
                    $join->on('ratings.ticket_id','tickets.id');
                    $join->on('ratings.company_id','tickets.company_id');
               })
               ->where($source, $sourceId)
               ->whereNotNull('tickets.customer_id')
               ->whereNull('ratings.id')
               ->select([
                    'tickets.id',
                    'tickets.product_name',
                    'tickets.product_category',
                    'tickets.product_id',
                    'tickets.company_id',
                    'tickets.customer_id',
                    'tickets.current_agent_id',
                    'tickets.ticket_number',
                    'tickets.source',
                    'tickets.customer_name',
                    'tickets.status_id',
                    'tickets.status_table'
               ])
               ->get();
     }

     public function findDataRating($ticketId)
     {
          return DB::table('tickets as t')
               ->join('users as u', 't.customer_id', 'u.id')
               ->join('companies as c', 't.company_id', 't.id')
               ->join('ratings as r', 'r.ticket_id', 't.id')
               ->where('t.id', $ticketId)
               ->select([
                    'u.id',
                    'u.regid',
                    'u.platform',
                    't.product_name',
                    'c.name as company_name',
                    'c.logo as picture',
                    'r.id as rating_id'
               ])
               ->first();
     }

     public function findDataTicketByTicketIdAndCustomerId($ticketId, $customerId)
     {
          return $this->model::join('companies', 'companies.id', 'tickets.company_id')
               ->where('tickets.id', $ticketId)
               ->where('tickets.customer_id', $customerId)
               ->select([
                    'tickets.id',
                    'tickets.company_id',
                    'tickets.current_agent_id as agent_id',
                    'tickets.product_name',
                    'companies.name as company_name',
                    'companies.logo as company_logo'
               ])
               ->firstOrFail();
     }

     public function findCsatTemplateByTicketId($ticketId)
     {
          $csatItems = [];
          $freeText = false;

          $ticket = $this->model::query()
               ->leftJoin('company_products', 'company_products.id', 'tickets.product_id')
               ->Where('tickets.id', $ticketId)
               ->select(['company_products.csat_template_id', 'tickets.product_category', 'tickets.type', 'tickets.company_id'])
               ->first();


          $csatTemplateId = $ticket?->csat_template_id;
          if ($ticket?->product_category == 'Other' && $ticket?->type == 'inbound') {
               $otherProduct = DB::table('product_others')
                    ->where('type', 'inbound')
                    ->where('company_id', $ticket?->company_id)
                    ->select(['csat_template_id'])
                    ->first();
               $csatTemplateId = $otherProduct?->csat_template_id;
          }

          if ($csatTemplateId) {
               $template = DB::table('csat_templates')
                    ->where('company_id', $ticket?->company_id)
                    ->where('id', $csatTemplateId)
                    ->first();
               $csatItems = DB::table('csat_template_items')
                    ->where('company_id', $ticket?->company_id)
                    ->where('csat_template_id', $csatTemplateId)
                    ->orderBy('sorting', 'asc')
                    ->pluck('text');
               
               $freeText = $template?->has_free_text;
          }

          return [
               'has_csat' => count($csatItems) ? true : false,
               'free_text' => $freeText,
               'csat_items' => $csatItems
          ];
     }
}
