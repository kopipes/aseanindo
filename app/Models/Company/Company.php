<?php
namespace App\Models\Company;

use App\Models\Data\Category;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'companies';
    protected $guarded = [];

    public function scopeWithTotalFollower($query){
        $query->leftJoin(
            DB::raw('(select company_id,count(user_id) follower from followings group by company_id) AS follower'),
            function ($join) {
                $join->on('companies.id', '=', 'follower.company_id');
            }
        )->addSelect('follower.follower');
    }

    public function scopeWithTotalRating($query){
        $query->leftJoin(
            DB::raw('(select company_id,avg(rating) rating from ratings group by company_id) AS ratings'),
            function ($join) {
                $join->on('companies.id', '=', 'ratings.company_id');
            }
        )->addSelect('ratings.rating');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(CompanyProfile::class, 'company_id', 'id');
    }
}
