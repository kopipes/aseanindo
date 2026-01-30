<?php

namespace App\Models\Util;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SipExtension extends Model
{
    use HasFactory;
    protected $table = 'cms_extension';

    protected $guarded = [];

    public $timestamps = false;
}
