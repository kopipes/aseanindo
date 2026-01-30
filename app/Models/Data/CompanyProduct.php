<?php

namespace App\Models\Data;

use App\Models\Company\Company;
use App\Models\Data\ProductHelpdesk;
use App\Models\Data\ProductScheduleDetail;
use App\Models\Data\TicketScheduleDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyProduct extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'company_products';
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:d M Y',
        'chatbot_forms' => 'array'
    ];


    public function helpdesk()
    {
        return $this->hasMany(ProductHelpdesk::class, 'product_id', 'id');
    }

    public function helpdeskName()
    {
        return $this->hasMany(ProductHelpdesk::class, 'product_id', 'id')
            ->join('company_helpdesk_categories', 'company_helpdesk_categories.id', 'product_helpdesk.helpdesk_id');
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'company_id')
            ->leftJoin('company_profile', 'company_profile.company_id', 'companies.id')
            ->select(['companies.*', 'company_profile.city', 'company_profile.address']);
    }

    public function detail()
    {
        return $this->hasOne(ProductScheduleDetail::class, 'product_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(TicketScheduleDetail::class, 'product_id', 'id');
    }
}
