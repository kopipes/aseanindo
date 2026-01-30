<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAgent extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'ticket_agents';

    protected $guarded = [];
}
