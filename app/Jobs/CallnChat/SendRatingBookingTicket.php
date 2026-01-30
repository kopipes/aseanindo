<?php

namespace App\Jobs\CallnChat;

use App\Models\Data\Rating;
use Illuminate\Bus\Queueable;
use App\Models\Data\Notification;
use Illuminate\Support\Facades\DB;
use App\Mail\Chat\SendRatingTicket;
use Illuminate\Support\Facades\Mail;
use App\Services\Ticket\TicketService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendRatingBookingTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ticketService;
    /**
     * Create a new job instance.
     */
    public function __construct(
        public $company,
        public $customerEmail,
        public $customerName,
        public $ticket,
        public $queueNumber = null
    ) {
        $this->ticketService = new TicketService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            $ticket = $this->ticket;
            $productType = 'product';
            $productName = $ticket->product_name;
            $ticketProductCategory = $ticket->product_category;
            $notificationType = $ticket->product_id ? 'rating_product' : 'rating_consultation';
            if (in_array($ticketProductCategory, ['Schedule Professional', 'Schedule Other'])) {
                $productType = 'schedule';
                $ticketProductCategory = 'Information';
                $productName = 'Booking Schedule';
            }

            $rating = Rating::create([
                'company_id' => $ticket->company_id,
                'users_id' => $ticket->customer_id,
                'agent_id' => $ticket->current_agent_id,
                'category' => $ticketProductCategory === 'General' ? 'General Product' : $ticketProductCategory,
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'product_type' => $productType,
                'product_name' => $productName,
                'product_id' => $ticket->product_id,
                'rating' => 0,
            ]);

            Notification::create([
                'user_id' => $ticket->customer_id,
                'user_role' => 'customer',
                'company_id' => $ticket->company_id,
                'category' => 'notification',
                'title' => $this->company->name,
                'description' => $productName,
                'type' => $notificationType,
                'parent_id' => $rating->id,
                'details' => ['rating' => 0]
            ]);

            /**
             * If Ticket source "From Web" Send rating to email else send fcm
             */
            try {
                Mail::to($this->customerEmail)->send(
                    new SendRatingTicket($this->customerName, $ticket->id, 1, $ticket->company_id, null, null, null, $this->queueNumber)
                );
            } catch (\Exception $e) {
            }
        });
    }

}
