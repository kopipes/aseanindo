<?php

namespace App\Mail\Chat;

use App\Models\Chat\ChatMessage;
use App\Models\Company\CompanyProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SendHistoryChatGuest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public $customerName,
        public $chatId,
        public $companyId,
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'History Chat',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $conversation = ChatMessage::query()
            ->leftJoin('users', 'users.id', 'chat_messages.user_id')
            ->leftJoin('company_users',function($join){
                $join->on('company_users.user_id','chat_messages.user_id');
                $join->on('company_users.company_id','chat_messages.company_id');
            })
            ->where('chat_messages.chat_id', $this->chatId)
            ->select([
                'chat_messages.created_at',
                'chat_messages.message',
                'chat_messages.message_type',
                DB::raw("ifnull(ifnull(company_users.name,users.name),'Guest') as user_name")
            ])
            ->orderBy('chat_messages.created_at', 'asc')
            ->get()
            ->each(function($row){
                $row->created_at = date('Y-m-d H:i:s',strtotime($row->created_at));
                $row->message = in_array($row->message_type,['location','file']) ? json_decode($row->message) : $row->message;
            });
        $companyProfile = CompanyProfile::query()
            ->where('company_id', $this->companyId)
            ->select(['brand_name'])
            ->first();

        return new Content(
            view: 'emails.callchat.history-chat',
            with: [
                'customer_name' => $this->customerName,
                'ba_name' => $companyProfile->brand_name,
                'conversation' => $conversation,
                'date' => date('D, d M Y H:i:s'),
            ],
        );
    }

}
