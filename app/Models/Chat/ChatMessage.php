<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatMessage extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'chat_messages';

    protected $guarded = [];

    protected $casts = [
        'time' => 'datetime:H:i',
        'date_time' => 'datetime:Y-m-d H:i:s',
    ];

    protected function content(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (in_array($this->type, ['message', 'broadcast', 'information'])) {
                    return $value;
                } else {
                    return $value ? json_decode($value) : null;
                }
            },
        );
    }

    protected function profile(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? asset($value) : 'https://yelow-app-storage.s3.ap-southeast-1.amazonaws.com/cnako525c6GTGkUq1nefIJ38mXinpV5JovDMuuws.png',
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ?: 'Guest',
        );
    }
}