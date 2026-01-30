<?php

namespace App\Models\Call;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'call_logs';

    protected $guarded = [];
}
