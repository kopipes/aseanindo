<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductHelpdesk extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'product_helpdesk';

    protected $guarded = [];
}
