<?php

namespace App\Helpers;

use App\Helpers\Agora\Agora;
use App\Helpers\Agora\AgoraRecording;
use App\Helpers\Queue;
use Illuminate\Support\Facades\Http;


class CallRecordingSystem
{
     public static function startAgoraRecording($companyName, $call)
     {
          sleep(1);
          Queue::dispatch(function () use ($call, $companyName) {
               $callUid = $call->uuid;
               $callChannel = $call->channel_name;
               if ($agoraResourceId = AgoraRecording::init()->createResourceId($callChannel, $callUid)) {
                    if ($record = AgoraRecording::init()->startRecordCall($companyName, $agoraResourceId, $callChannel, $callUid)) {
                         $call->update([
                              'agora_resource_id' => $record['resourceId'],
                              'agora_sid' => $record['sid']
                         ]);
                    }
               }
          });
     }

     public static function stopAgoraRecording($call)
     {
          Queue::dispatch(function () use ($call) {
               $callChannel = $call->channel_name;
               $callUid = $call->uuid;
               $resourceId = $call->agora_resource_id;
               $sid = $call->agora_sid;
               if ($resourceId && $sid) {
                    if ($recordUrl = AgoraRecording::init()->stopRecordCall($callChannel, $callUid, $resourceId, $sid)) {
                         $call->update([
                              'record_url' => $recordUrl
                         ]);
                    }
               }
          });
     }

     public static function endSipCall($call, $agentExtension, $customerPhone,$secondTime = false)
     {
          Queue::dispatch(function () use ($agentExtension, $customerPhone, $call,$secondTime) {
               $pstn_id = $call->pstn_id;
               if ($customerPhone && !$pstn_id) {
                    $customerPhone = Yellow::sipNumber($customerPhone);
               }
               if ($pstn_id) {
                    $agentExtensionReal = $agentExtension;
                    $agentExtension = $customerPhone;
                    $customerPhone = $agentExtensionReal;
               }
               $dateTime = $call->created_at;
               $date = date('Y-m-d', strtotime($dateTime));
               $endPoint = config('services.sip.api_url');
               $endPoint .= "/cdr/get-all-cdr?date={$date}&extension={$agentExtension}&destination={$customerPhone}";
               $result = Http::withHeaders(['API-KEY' => config('services.sip.api_key')])
                    ->withOptions(["verify" => false])
                    ->get($endPoint)->json();
               $data = @$result['data'];
               if ($result && $data) {
                    $data = collect($data)->where('calldate', $date)->first();
                    if ($data) {
                         $call->update([
                              'sip' => $data['uniqueid']
                         ]);
                    }
               }else{
                    if(!$secondTime){
                         self::endSipCall($call,$customerPhone,$agentExtension,true);
                    }
               }
          });

     }


     public static function getIVRSipDuration($call,$agentExtension,$customerPhone)
     {
          $pstn_id = $call->pstn_id;
          if($pstn_id){
               $dateTime = $call->created_at;
               $date = date('Y-m-d', strtotime($dateTime));
               $endPoint = config('services.sip.api_url');
               $endPoint .= "/cdr/get-all-cdr?date={$date}&extension={$customerPhone}&destination=";
               $result = Http::withHeaders(['API-KEY' => config('services.sip.api_key')])
                    ->withOptions(["verify" => false])
                    ->get($endPoint)->json();
               $data = @$result['data'];
               if ($result && $data) {
                    $data = collect($data)
                         ->where('calldate', $date)
                         ->where('dst','!=',$agentExtension)
                         ->first();
                    if ($data) {
                         return intval(@$data['duration']);
                    }
               }
          }
          
          return 0;
     }
}