<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyProfile extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'company_profile';
    protected $guarded = [];

    protected $casts = [
        'id_card_properties' => 'array',
        'sppkp_properties' => 'array'
    ];

}
