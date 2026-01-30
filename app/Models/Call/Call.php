<?php

namespace App\Models\Call;

use App\Models\Data\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Call extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'calls';

    protected $guarded = [];

    protected $casts = [
        'sip_extension' => 'array'
    ];

    public function agent(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'agent_id')
            ->select([
                'users.id','users.name','users.email','users.phone'
            ]);
    }
}
