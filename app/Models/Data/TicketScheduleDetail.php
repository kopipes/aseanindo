<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketScheduleDetail extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'ticket_schedule_detail';

    protected $guarded = [];

    protected $casts = [
        'counseling_time' => 'array'
    ];
}
