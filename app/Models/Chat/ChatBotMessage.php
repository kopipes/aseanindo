<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatBotMessage extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'chatbot_messages';

    protected $guarded = [];

    protected $appends = ['time'];

    protected $casts = [
        'customer_data' => 'array'
    ];

    protected function message(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ((in_array($this->message_type, ['message', 'broadcast', 'information']) && $this->sender === 'customer') || $this->message_type=='rejection') {
                    return $value;
                } else {
                    $message = $value ? json_decode($value) : null;
                    if($message && $this->message_type==='bot_message_option'){
                        $message->options = collect($message->options)->map(function($row){
                            unset($row->next);
                            return $row;
                        })->values()->toArray();
                    }
                    return $message;
                }
            },
        );
    }

    protected function time(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return date('H:i',strtotime($this->created_at));
            },
        );
    }

}
