<?php

namespace App\Helpers;

use App\Helpers\FCM;
use App\Helpers\SocketBroadcast;

class BroadcastNotification
{
     /**
      * BroadcastNotification::to($user)->send([],'chat-ended');
      */
     protected static $user;
     protected static $channel = null;
     protected static $data = [];

     public static function channel($channel)
     {
          self::$channel = $channel;
          return new static();
     }

     public static function to($user)
     {
          self::$user = $user;
          return new static();
     }

     public static function send($data)
     {
          $user = self::$user;
          if ($user?->platform === 'web') {
               $channel = self::$channel ?? $data['type'];
               if ($channel && $user?->id) {
                    return SocketBroadcast::channel($channel)
                         ->destination([$user->id])
                         ->send($data);
               }
          } else {
               if ($user?->regid) {
                    return FCM::post($user?->regid, $user?->platform)->send($data);
               }
          }
          return null;
     }

     public static function dispatch($data)
     {
          $user = self::$user;
          if ($user?->platform === 'web') {
               $channel = self::$channel ?? $data['type'];
               if ($channel && $user?->id) {
                    return SocketBroadcast::channel($channel)
                         ->destination([$user->id])
                         ->dispatch($data);
               }
          } else {
               if ($user?->regid) {
                    return FCM::post($user?->regid, $user?->platform)->dispatch($data);
               }
          }
          return null;
     }
}
