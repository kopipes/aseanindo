<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketHistory extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'ticket_histories';

    protected $guarded = [];
    
}
