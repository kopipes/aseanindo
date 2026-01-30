<?php
namespace App\Models\Data;


use App\Models\Data\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Company\CompanyUser;

class UserHelpdesk extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'user_helpdesk';

    protected $guarded = [];

 

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function companyUser(): HasOne
    {
        return $this->hasOne(CompanyUser::class, 'id', 'company_user_id');
    }
}