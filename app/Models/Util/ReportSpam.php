<?php

namespace App\Models\Util;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSpam extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'report_spams';

    protected $guarded = [];
}
