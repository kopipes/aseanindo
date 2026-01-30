<?php
namespace App\Models\Company;

use App\Enum\ActivityStatus;
use App\Models\Data\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CompanyUser extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'company_users';

    protected $guarded = [];

    protected $casts = [
        'activity' => ActivityStatus::class
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}