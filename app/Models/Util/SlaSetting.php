<?php

namespace App\Models\Util;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaSetting extends Model
{
    use HasFactory, HasUuids;

    protected $table = "company_sla_settings";

    protected $casts = [
        'critical' => 'array',
        'hight' => 'array',
        'medium' => 'array',
        'low' => 'array',
        'office_hour' => 'array'
    ];

}
