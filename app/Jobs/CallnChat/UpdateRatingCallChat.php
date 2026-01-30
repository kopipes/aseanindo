<?php

namespace App\Jobs\CallnChat;

use App\Helpers\BroadcastNotification;
use App\Models\Data\Notification;
use App\Models\Data\Rating;
use App\Services\Ticket\TicketService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateRatingCallChat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public $request
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $request = $this->request;
        if ($ticketId = @$request['ticket_id']) {
            $companyId = @$request['company_id'];
            $ratingStar = @$request['rating'];
            $review = @$request['review'];
            $agentId = @$request['agent_id'];
            $csatRating = @$request['csat_rating'];

            $rating = Rating::where('company_id', $companyId)
                // ->where('agent_id', $agentId)
                ->where('ticket_id', $ticketId)
                ->orderBy('created_at','desc')
                ->first();
            if ($rating) {
                $rating->update([
                    'rating' => $ratingStar,
                    'review' => $review,
                    'csat_rating' => $csatRating
                ]);
                Notification::where('parent_id', $rating->id)
                    ->update([
                            'details' => ['rating' => $ratingStar]
                        ]);

                if ($dataRating = (new TicketService)->findDataRating($ticketId)) {
                    BroadcastNotification::to($dataRating)->dispatch($dataRating);
                }
            }
        }

    }
}
