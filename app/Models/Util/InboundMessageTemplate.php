<?php

namespace App\Models\Util;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InboundMessageTemplate extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inbound_message_templates';

    protected $guarded = [];


    public static function endChat($companyId)
    {
        return self::where('company_id', $companyId)
            ->where('category', 'end_chat')
            ->first()?->content
            ?: ''; //'Thank you for chatting with us today. Have a great day!';
    }
}
