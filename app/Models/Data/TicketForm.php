<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketForm extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'ticket_forms';

    protected $guarded = [];
}
