<?php

namespace App\Helpers\Agora;

use App\Helpers\Agora\Agora;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AgoraRecording
{
     protected static $agora_url = "";
     protected static $auth_token = "";
     public static function init()
     {
          $agoraAppId = config('services.agora.app_id');
          self::$agora_url = "https://api.agora.io/v1/apps/{$agoraAppId}/cloud_recording";
          self::$auth_token = "Basic " . base64_encode(
               config('services.agora.api_key') . ":" . config('services.agora.api_secret')
          );
          return new static();
     }


     public static function createResourceId($channelName, $uid)
     {
          try {
               $url = self::$agora_url . "/acquire";
               $request = Http::withHeaders([
                    'Content-Type' => "application/json",
                    'Authorization' => self::$auth_token
               ])->post($url, [
                              'cname' => $channelName,
                              'uid' => strval($uid),
                              'clientRequest' => [
                                   'resourceExpiredHour' => 24,
                                   'scene' => 0
                              ]
                         ]);
               if ($request->status() === 200) {
                    $result = $request->json();
                    return $result['resourceId'];
               }
          } catch (\Exception $e) {
               logger("AGORA CREATE RESOURCE ID ERROR", [
                    $e->getMessage()
               ]);
          }
          return null;
     }


     public static function startRecordCall($companyName, $resourceId, $channelName, $uid)
     {
          try {
               $url = self::$agora_url . "/resourceid/{$resourceId}/mode/mix/start";
               $request = Http::withHeaders([
                    'Content-Type' => "application/json",
                    'Authorization' => self::$auth_token
               ])->post($url, [
                              'cname' => $channelName,
                              'uid' => strval($uid),
                              'clientRequest' => [
                                   'token' => Agora::createToken($channelName, $uid),
                                   'recordingConfig' => [
                                        'streamTypes' => 0,
                                   ],
                                   'storageConfig' => [
                                        'accessKey' => config('services.agora.storage.access_key'),
                                        'region' => 24,
                                        'bucket' => config('services.agora.storage.bucket'),
                                        'secretKey' => config('services.agora.storage.secret_key'),
                                        'vendor' => 1,
                                        'fileNamePrefix' => [
                                             'Recording',
                                             Str::slug($companyName, ''),
                                             date('Y'),
                                             date('m'),
                                             date('d')
                                        ]
                                   ],
                                   'recordingFileConfig' => [
                                        'avFileType' => ['mp4', 'hls']
                                   ]
                              ]
                         ]);
               if ($request->status() === 200) {
                    $result = $request->json();
                    return $result;
               }
          } catch (\Exception $e) {
               logger("AGORA START RECORD ERROR", [
                    $e->getMessage()
               ]);
          }
          return null;
     }

     public static function stopRecordCall($channelName, $uid, $resourceId, $sid)
     {
          try {
               $url = self::$agora_url . "/resourceid/{$resourceId}/sid/{$sid}/mode/mix/stop";
               $request = Http::withHeaders([
                    'Content-Type' => "application/json",
                    'Authorization' => self::$auth_token
               ])->post($url, [
                              'cname' => $channelName,
                              'uid' => strval($uid),
                              'clientRequest' => (object) []
                         ]);
               if ($request->status() === 200) {
                    $result = $request->json();
                    if ($serverResponse = $result['serverResponse']) {
                         $files = $serverResponse['fileList'];

                         $fileName = $files;
                         if (is_array($files) && count($files) >= 2) {

                              $fileName = @collect($files)->filter(function ($row) {
                                   return str_contains($row['fileName'], '.mp4');
                              })->first()['fileName'];

                              try {
                                   // Delete .m3u8 recording file
                                   $recordingPath = substr($fileName, 0, strrpos($fileName, '/'));
                                   $m3u8File = @collect($files)->filter(function ($row) {
                                        return str_contains($row['fileName'], '.m3u8');
                                   })->first()['fileName'];
                                   self::deleteM3u8andTsRecordFile($recordingPath,$m3u8File);
                              } catch (\Exception $e) {
                                   logger("ERROR GET FILE RECORDING", [
                                        $e->getMessage()
                                   ]);
                              }
                         }

                         return config('services.agora.record_url') . "/" . $fileName;
                    }
               }
          } catch (\Exception $e) {
               logger("AGORA STOP RECORD ERROR", [
                    $e->getMessage()
               ]);
               throw new \Exception($e);
          }
          return null;
     }

     public static function deleteM3u8andTsRecordFile($path, $m3u8File)
     {
          if (Storage::disk('record')->exists($m3u8File)) {
               Storage::disk('record')->delete($m3u8File);
          }
          $listFiles = Storage::disk('record')->files($path);
          foreach ($listFiles as $fileUrl) {
               if (str_contains($fileUrl, '.ts')) {
                    Storage::disk('record')->delete($fileUrl);
               }
          }
     }
}