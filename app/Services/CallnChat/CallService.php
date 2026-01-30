<?php

namespace App\Services\CallnChat;

use App\Models\Call\Call;

class CallService
{
     public function __construct(
          public $model = Call::class
     ) {
     }

     public function findActiveCallAgent($agentId, $customerId = null)
     {
          return $this->model::where('agent_id', $agentId)
               ->where('status', 'talking')
               ->where('customer_id', '!=', $customerId)
               ->first();
     }

     public function findOneActiveCallAgent($agentId)
     {
          return $this->model::where('agent_id', $agentId)
               ->where('status', 'talking')
               ->first();
     }


     public function findActiveCallCustomer($customerId)
     {
          return $this->model::where('customer_id', $customerId)
               ->where('status', 'talking')
               ->first();
     }

     public function findCallById($callId)
     {
          return $this->model::where('id', $callId)
               ->select([
                    'id', 'status', 'agent_id', 'customer_id', 'guest_customer_id', 'start_at', 'category','created_at','ticket_id','source_category',
                    'uuid', 'channel_name', 'agora_resource_id', 'agora_sid', 'type', 'company_id','sip','spv_id','temp_spv_id','pstn_id','sip_extension','customer_phone_number'
               ])
               ->first();
     }

     public function findDetailCall($callId)
     {
          return $this->model::with(['agent'])
               ->join('company_users as cp_agent', function ($join) {
                    $join->on('cp_agent.user_id', 'calls.agent_id');
                    $join->on('cp_agent.company_id', 'calls.company_id');
               })
               ->leftJoin('call_logs', function ($join) {
                    $join->on('call_logs.call_id', 'calls.id');
                    $join->where('call_logs.role', 'spv');
                    $join->whereNull('call_logs.leave_at');
               })
               ->leftJoin('users as spv', 'spv.id', 'call_logs.user_id')
               ->leftJoin('company_users as cp_spv', function ($join) {
                    $join->on('cp_spv.user_id', 'spv.id');
                    $join->on('cp_spv.company_id', 'calls.company_id');
               })
               ->where('calls.id', $callId)
               ->select([
                    'calls.*', 'cp_agent.profile as profile_agent', 'cp_spv.profile as profile_spv',
                    'spv.id as spv_id','spv.name as spv_name','spv.email as spv_email','spv.phone as spv_phone','call_logs.join_at as spv_join_at'
               ])
               ->firstOrFail();
     }

     public function deleteMissedCallByCustomerAndAgent($customerId, $agentId)
     {
          return $this->model::where('category', 'Missed Call')
               ->where('customer_id', $customerId)
               ->where('agent_id', $agentId)
               ->delete();
     }
}
