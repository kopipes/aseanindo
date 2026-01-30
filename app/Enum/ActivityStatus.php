<?php
namespace App\Enum;

enum ActivityStatus: string
{
     case Talking = "Talking";
     case Chatting = "Chatting";
     case CallnChat = "CallnChat";
     case Online = "Online";
}