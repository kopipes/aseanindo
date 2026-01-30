<?php

namespace App\Models\Util;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpToken extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'otp_token';

    protected $guarded = [];

    
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        self::creating(function ($otpToken) {
            OtpToken::where('source', $otpToken->source)->delete();
        });
    }
    public function scopeActiveToken($query, $email,$token)
    {
        $query->whereEmail($email)->where('token',$token)->where('available_until', '>=', now());
    }
}
