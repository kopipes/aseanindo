<?php
namespace App\Helpers\Agora;

class Agora
{
     public static function createToken($channelName, $user)
     {
          $appID = config('services.agora.app_id');
          $appCertificate = config('services.agora.app_certificate');

          $role = RtcTokenBuilder::RolePublisher;
          $expireTimeInSeconds = 43200; // 12hour in second
          $currentTimestamp = now()->getTimestamp();

          $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

          return RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);
     }

     public static function createUserId($userId)
     {
          return crc32($userId) & 0xFFFFFFFF;
     }
}