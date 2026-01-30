<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatBotFlowTemplate extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'chatbot_flow_templates';

    protected $guarded = [];

    protected $casts = [
        'flow' => 'array',
        'scene' => 'array',
        'another_flows' => 'array'
    ];
}
