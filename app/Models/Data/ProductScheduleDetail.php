<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductScheduleDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'products_schedule_detail';
    protected $guarded = [];

    protected $casts = [
        'professional_type' => 'array',
        'location' => 'array',
    ];
}
