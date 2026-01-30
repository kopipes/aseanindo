<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatBot extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'chatbots';

    protected $guarded = [];

    protected $casts = [
        'all_flows' => 'array',
        'flow' => 'array',
        'current_scene' => 'array',
        'next_scene' => 'array',
        'current_step' => 'array',
        'customer_data' => 'array',
        'product_form_scene' => 'array',
        'verification_token' => 'array'
    ];
}
