<?php

namespace App\Models\Util;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportReason extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'report_spam_reasons';

    protected $guarded = [];


    public function scopeFindAllByType($query, $type)
    {
        return $query->where('type', $type)->latest()->get(['reason']);
    }
}
