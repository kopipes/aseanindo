<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyContactCustomer extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'company_customer_contacts';

    protected $guarded = [];
}
