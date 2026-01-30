<?php

namespace App\Mail\Chat;

use App\Models\Company\CompanyProfile;
use App\Models\Data\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendRatingTicket extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public $customerName,
        public $ticketId,
        public $rating,
        public $companyId,
        public $additionalContent = null,
        public $emailSubject = null,
        public $currentAgent = null,
        public $queueNumber = null
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject ?: 'Summary ticket',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $ticket = Ticket::query()
            ->with([
                'bookingDetails',
                'product:id,name,image',
                'product.detail:id,product_id,pic_name'
            ])
            ->leftJoin('company_users', function ($join) {
                $join->on('company_users.company_id', 'tickets.company_id');
                $join->on('company_users.user_id', 'tickets.current_agent_id');
            })
            ->leftJoin('company_users as last_agent', function ($join) {
                $join->on('last_agent.company_id', 'tickets.company_id');
                $join->on('last_agent.user_id', 'tickets.last_agent_id');
            })
            ->where('tickets.id', $this->ticketId)
            ->select(['tickets.*', 'company_users.name as agent','last_agent.name as last_agent_name'])
            ->firstOrFail();
        $ticket->date = date('Y-m-d', strtotime($ticket->created_at));
        $ticket->time = date('H:iA', strtotime($ticket->created_at));
        $ticket->created_at = date('Y-m-d H:i:s', strtotime($ticket->created_at));
        $ticket->product_category = in_array($ticket->product_category, ['General', 'Other', 'Campaign']) ? 'Product' : $ticket->product_category;
        $ticket->bookingDetails = $ticket->bookingDetails->map(function($booking){
            $booking->time = '-';
            if($booking->counseling_time){
                $counseling_time = $booking->counseling_time;
                $booking->time = $counseling_time['start'] ." - ".$counseling_time['end'];
            }
            return $booking;
        });


        $agentName = $ticket?->agent ?: $ticket?->last_agent_name;
        // if($this->currentAgent){
        //     if($ticket->current_agent_id!=$this->currentAgent->id){
        //         $agentName = $this->currentAgent->name;
        //     }
        // }
        $companyProfile = CompanyProfile::query()
            ->where('company_id', $this->companyId)
            ->select(['brand_name'])
            ->first();
        return new Content(
            view: 'emails.callchat.rating-review',
            with: [
                'customer_name' => $this->customerName,
                'ba_name' => $companyProfile->brand_name,
                'rating' => $this->rating,
                'ticket' => $ticket,
                'agentName' => $agentName ?: '-',
                'additionalContent' => $this->additionalContent,
                'queueNumber' => $this->queueNumber
            ],
        );
    }
}
