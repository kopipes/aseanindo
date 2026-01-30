<?php

namespace App\Models\Data;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyFaq extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'company_faqs';

    protected $guarded = [];

    protected $appends = ['content'];

    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::limit(strip_tags($this->description), 90),
        );
    }

    public function subs()
    {
        return $this->hasMany(CompanyFaq::class, 'parent_id', 'id')->orderByRaw('company_faqs.sorting asc,company_faqs.created_at asc');
    }


    public function sub()
    {
        return $this->hasMany(CompanyFaq::class, 'parent_id', 'id')
            ->leftJoin("company_products", function ($join) {
                $join->on("company_products.faq_id", "company_faqs.id");
                $join->on("company_products.company_id", "company_faqs.company_id");
            })
            ->with(["child"])
            ->select(['company_faqs.id', 'company_faqs.name', 'company_faqs.description','company_faqs.parent_id', 'company_products.id as product_id'])
            ->orderByRaw('company_faqs.sorting asc,company_faqs.created_at asc');
    }

    public function child()
    {
        return $this->hasMany(CompanyFaq::class, 'parent_id', 'id')
        ->leftJoin("company_products", function ($join) {
            $join->on("company_products.faq_id", "company_faqs.id");
            $join->on("company_products.company_id", "company_faqs.company_id");
        })
            ->select(['company_faqs.id', 'company_faqs.name', 'company_faqs.description','company_faqs.parent_id', 'company_products.id as product_id'])
            ->orderByRaw('company_faqs.sorting asc,company_faqs.created_at asc');
    }
}
