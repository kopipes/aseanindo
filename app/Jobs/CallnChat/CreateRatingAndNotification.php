<?php

namespace App\Jobs\CallnChat;

use App\Helpers\BroadcastNotification;
use App\Helpers\FCM;
use App\Mail\Chat\SendRatingTicket;
use App\Models\Company\CompanyUser;
use App\Models\Data\Notification;
use App\Models\Data\Rating;
use App\Models\Data\User;
use App\Services\Ticket\TicketService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Condition
 * - Collect All ticket list by chat id
 * - Set first rating is 0, if user submit rating update rating value
 * - Send notification FCM into customer
 * - If Ticket source "From Web"
 * - Emit refresh agent BA list
 */
class CreateRatingAndNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ticketService;
    /**
     * Create a new job instance.
     */
    public function __construct(
        public $company,
        public $customer,
        public $sourceId,
        public $sourceType,
        public $agentId,
    ) {
        $this->ticketService = new TicketService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $ticketList = $this->ticketService->findAllTicketBySourceId(
            source: $this->sourceType === 'chat' ? 'tickets.chat_id' : 'tickets.call_id',
            sourceId: $this->sourceId
        );
        foreach ($ticketList as $ticket) {
            /**
             * Step
             * 1. Insert rating table
             * 2. Insert notification
             * 3. Send fcm to customer
             * 4. If Ticket source "From Web" Send rating to email
             */
            DB::transaction(function () use ($ticket) {
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
                    'agent_id' => $this->agentId ?: $ticket->current_agent_id,
                    'category' => $ticketProductCategory === 'General' ? 'General Product' : $ticketProductCategory,
                    'ticket_id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'product_type' => $productType,
                    'product_name' => $productName,
                    'product_id' => $ticket->product_id,
                    'rating' => null,
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
                if (in_array($ticket->source, ['From Web', 'From Incoming SIP', 'From Web Bot', 'From Whatsapp Bot','From Whatsapp','From Email','From SIP','From Facebook','From Instagram'])) {
                    $user = User::query()
                        ->where('id', $ticket->customer_id)
                        ->select(['email', 'name'])
                        ->first();
                    if ($user) {
                        $statusEmail = $this->statusEmailByTicket($ticket);
                        $currentAgent = CompanyUser::query()
                            ->where('user_id', $this->agentId)
                            ->where('company_id', $ticket->company_id)
                            ->select(['user_id', 'name'])
                            ->first();
                        Mail::to($user->email)->send(
                            new SendRatingTicket(
                                $ticket->customer_name,
                                $ticket->id,
                                1,
                                $ticket->company_id,
                                $statusEmail?->email_customer_content,
                                $statusEmail?->subject,
                                $currentAgent
                            )
                        );
                    }
                } else if ($this->customer && $this->customer->regid) {
                    BroadcastNotification::to($this->customer)
                        ->dispatch([
                            'type' => $notificationType,
                            'title' => $this->company->name,
                            'message' => $productName,
                            'icon' => asset($this->company->logo),
                            'parent_id' => $rating->id,
                            'ticket_id' => $ticket->id,
                            'details' => ['rating' => 0]
                        ]);
                }
            });
        }
    }

    private function statusEmailByTicket($ticket)
    {
        if ($ticket->status_id) {
            return DB::table($ticket?->status_table)
                ->where('id', $ticket->status_id)
                ->select(['email_customer_content', 'subject'])
                ->first();
        }
        return null;
    }
}
