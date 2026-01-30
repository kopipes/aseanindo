<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Nexmo
{

     private static $api_key;
     private static $api_secret;
     private static $signature;
     private static $to;
     private static $from;
     private static $message;

     public function __construct()
     {
          self::$api_key = config('services.nexmo.api_key');
          self::$api_secret = config('services.nexmo.api_secret');
          self::$signature = config('services.nexmo.signature');
     }
     public static function bodyParams()
     {
          return [
               'api_key' => self::$api_key,
               'api_secret' => self::$api_secret,
               'from' => self::$from,
               'to' => self::$to,
               'text' => self::$message,
               'type' => 'text',
               'v' => time()
          ];
     }

     public static function to($to)
     {

          self::$to  = $to;
          return new static();
     }

     public static function from($from)
     {

          self::$from  = $from;
          return new static();
     }

     public static function send($message)
     {
          self::$message  = $message;
          $bodyParams = self::bodyParams();
          return Http::post('https://rest.nexmo.com/sms/json',$bodyParams);
     }
}
