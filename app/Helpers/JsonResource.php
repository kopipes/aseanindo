<?php

namespace App\Helpers;


use Illuminate\Http\Resources\Json\JsonResource as BaseResource;

class JsonResource extends BaseResource
{
     protected static $using = [];

     public static function using($using = [])
     {
          static::$using = $using;
     }
}
