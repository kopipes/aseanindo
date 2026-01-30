<?php

namespace App\Actions\Call;

use App\Helpers\BroadcastNotification;
use App\Helpers\FCM;
use App\Jobs\CallnChat\UpdateRatingCallChat;
use App\Models\Data\Notification;
use App\Models\Data\Rating;
use App\Services\Ticket\TicketService;
use Illuminate\Http\Request;

class RatingLiveSession
{
     public function __construct(
          private $ticketService = new TicketService,
     ) {
     }
     public function handle(Request $request)
     {
          UpdateRatingCallChat::dispatch($request->all());
     }
}
