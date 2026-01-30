<?php

namespace App\Models\Data;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $table = 'users';

    protected $guarded = [];
    protected $hidden = ['password'];

    public function scopeWithCustomerFollower($query)
    {
        $query->leftJoin(
            DB::raw('(select user_id,count(company_id) as following from followings group by user_id) AS follower'),
            function ($join) {
                $join->on('users.id', '=', 'follower.user_id');
            }
        )->addSelect('follower.following');
    }
}
