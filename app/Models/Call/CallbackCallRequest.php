<?php

namespace App\Models\Call;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallbackCallRequest extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'call_callback_requests';

    protected $guarded = [];
}
