<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Bus;

class Queue
{
     public static function dispatch($callback)
     {
          Bus::chain([$callback])->dispatch();
     }
}