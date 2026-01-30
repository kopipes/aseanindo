<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBilling extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'company_billings';
    protected $guarded = [];

    protected $casts = [
        'summary' => 'array',
        'additional' => 'array'
    ];
}
