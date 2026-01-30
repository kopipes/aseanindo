<?php

namespace App\Services\CallnChat;

use App\Helpers\Queue;
use App\Models\Call\CallLog;
use Illuminate\Support\Str;

class CallLogService
{
     public function __construct(
          public $model = CallLog::class
     ) {
     }

     public function createNewLogs($call, $companyId, $customerId)
     {
          Queue::dispatch(
               fn() => [
                    $this->model::insert([
                         [
                              'id' => Str::uuid(),
                              'created_at' => now(),
                              'company_id' => $companyId,
                              'call_id' => $call->id,
                              'user_id' => $call->agent_id,
                              'role' => 'agent',
                              'join_at' => $call->start_at,
                         ],
                         [
                              'id' => Str::uuid(),
                              'created_at' => now(),
                              'company_id' => $companyId,
                              'call_id' => $call->id,
                              'user_id' => $customerId,
                              'role' => $customerId ? 'customer' : 'guest',
                              'join_at' => $call->start_at,
                         ]
                    ])
               ]
          );
     }

     public function findAllOnGoingLogs($callId){
          return $this->model::query()
          ->where('call_id', $callId)
          ->whereNull('leave_at')
          ->get();
     }

     public function createLogs($call, $companyId, $customerId, $leaveAt, $duration, $price, $callCost)
     {
          Queue::dispatch(
               fn() => [
                    $this->model::insert([
                         [
                              'id' => Str::uuid(),
                              'created_at' => now(),
                              'company_id' => $companyId,
                              'call_id' => $call->id,
                              'user_id' => $call->agent_id,
                              'role' => 'agent',
                              'join_at' => $call->start_at,
                              'leave_at' => $leaveAt,
                              'duration' => $duration,
                              'cost_per_second' => $callCost,
                              'cost' => $price
                         ],
                         [
                              'id' => Str::uuid(),
                              'created_at' => now(),
                              'company_id' => $companyId,
                              'call_id' => $call->id,
                              'user_id' => $customerId,
                              'role' => $customerId ? 'customer' : 'guest',
                              'join_at' => $call->start_at,
                              'leave_at' => $leaveAt,
                              'duration' => $duration,
                              'cost_per_second' => $callCost,
                              'cost' => $price
                         ]
                    ])
               ]
          );
     }

     public function startSpvLog($call, $spvId)
     {
          return $this->model::create([
               'created_at' => now(),
               'company_id' => $call->company_id,
               'call_id' => $call->id,
               'user_id' => $spvId,
               'role' => 'spv',
               'join_at' => now(),
          ]);
     }

     public function findSpvLogJoinCall($call, $spvId)
     {
          return $this->model::where('call_id', $call->id)
               ->where('company_id', $call->company_id)
               ->where('role', 'spv')
               ->where('user_id', $spvId)
               ->whereNull('leave_at')
               ->orderBy('created_at', 'desc')
               ->first();
     }

     public function findSpvLogCostDuration($call)
     {
          $spvLog = $this->model::selectRaw("sum(duration) as duration,sum(cost) as cost")
               ->where('call_id', $call->id)
               ->where('company_id', $call->company_id)
               ->where('role', 'spv')
               ->first();

          return (object) [
               'duration' => $spvLog->duration ?: 0,
               'cost' => $spvLog->cost ?: 0,
          ];
     }

     public function findCallLogCostDuration($call)
     {
          $isSip = $call->sip;
          $roles = ['agent','guest','customer','spv'];
          if($isSip){
               $roles = ['agent','spv'];
          }
          $spvLog = $this->model::selectRaw("sum(duration) as duration,sum(cost) as cost")
               ->whereIn('role',$roles)
               ->where('call_id', $call->id)
               ->where('company_id', $call->company_id)
               ->first();

          return (object) [
               'duration' => $spvLog->duration ?: 0,
               'cost' => $spvLog->cost ?: 0,
          ];
     }


     public function findAllSpvLogJoinCall($call, $spvId)
     {
          return $this->model::where('call_id', $call->id)
               ->where('company_id', $call->company_id)
               ->where('role', 'spv')
               ->where('user_id', $spvId)
               ->whereNull('leave_at')
               ->orderBy('created_at', 'desc')
               ->get();
     }


}