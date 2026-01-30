<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class Yellow
{
     public static function convertTextToHtml($text){
          $pattern = '/\b((http:\/\/|https:\/\/)?(www\.)?[\w\-]+\.[\w\.]+[^\s]*)\b/i';

          // Replace URLs with anchor tags
          $textWithLinks = preg_replace_callback($pattern, function($matches) {
              // Ensure the URL starts with http(s)://
              $url = (strpos($matches[0], 'http') === 0) ? $matches[0] : 'http://' . $matches[0];
              return "<a href=\"$url\" target=\"_blank\">$matches[0]</a>";
          }, $text);
      
          return $textWithLinks;
     }
     public static function createTicketNumber($type = null)
     {
          $prefix = 'PRO';
          switch ($type) {
               case 'outbound':
                    $prefix = 'MCO';
                    break;
               case 'schedule':
                    $prefix = 'SCH';
                    break;
               case 'general':
                    $prefix = 'GEN';
                    break;
          }
          return $prefix . date('YmdHis') . rand(111, 999);
     }
     public static function shareLink($category, $id)
     {
          return config('services.landing_page') . "/customer/$category/$id";
     }
     public static function createUsername($name)
     {
          $name = substr(strtolower(str($name)->replace(' ', '_')), 0, 7);
          $rand = rand(111, 999);
          return $name . $rand;
     }

     public static function uploadFile($file, $location = 'image')
     {
          // if (config('app.env') === 'development') {
          //      return Storage::disk('local')->put("uploads/{$location}", $file);
          // }
          $path = Storage::disk('s3')->put('', $file);
          return Storage::cloud()->url($path);
     }


     public static function deleteFile($url)
     {
          if ($url) {
               if ((!str_contains($url, 'http://') || !str_contains($url, 'https://')) && Storage::exists($url)) {
                    Storage::disk('local')->delete($url);
               } else {
                    $url = str_replace(config('filesystems.disks.s3.url'), "", $url);
                    if (Storage::disk('s3')->exists($url)) {
                         Storage::disk('s3')->delete($url);
                    }
               }
          }
     }

     public static function formatBytes($size, $precision = 2)
     {
          if ($size > 0) {
               $size = (int) $size;
               $base = log($size) / log(1024);
               $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

               return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
          } else {
               return $size;
          }
     }

     public static function phoneNumber($phone, $prefix = "62")
     {
          $start = substr($phone, 0, 1);
          $startTwo = substr($phone, 0, 2);
          $prefix = str_replace("+", '', $prefix);
          if ($start == '0') {
               return $prefix . ltrim($phone, "0");
          } else if ($startTwo !== $prefix) {
               return $prefix . $phone;
          }
          return $phone;
     }

     public static function randomPassword($length = 8)
     {

          $password = '';
          $passwordSets = ['1234567890', '%^&@*#()!', 'ABCDEFGHJKLMNPQRSTUVWXYZ', 'abcdefghjkmnpqrstuvwxyz'];

          //Get random character from the array
          foreach ($passwordSets as $passwordSet) {
               $password .= $passwordSet[array_rand(str_split($passwordSet))];
          }

          // 6 is the length of password we want
          while (strlen($password) < $length) {
               $randomSet = $passwordSets[array_rand($passwordSets)];
               $password .= $randomSet[array_rand(str_split($randomSet))];
          }
          return $password;
     }

     public static function phoneCountryCode()
     {
          return json_decode(file_get_contents(resource_path('assets/phone.json')), true);
     }

     public static function sipNumber($phone, $prefix = "62")
     {
          $prefixLength = strlen($prefix); 
          $phone = str_replace("+", '', $phone);
          $start = substr($phone, 0, $prefixLength);
          if ($start == $prefix) {
               return str_replace($prefix,"0",$phone);
          } 
          return $phone;
     }

}
