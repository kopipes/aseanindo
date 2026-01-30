<?php

namespace App\Http\Resources\Api\Helpdesk;

use App\Helpers\JsonResource;
use App\Helpers\Yellow;
use Illuminate\Http\Request;

class ProductItemResource extends JsonResource
{
    public static $favoriteProducts;

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $myFavoriteProduct = $request->get('FAVORITE_PRODUCT', []);
        return [
            'id' => $this->id,
            'image' => asset($this->image),
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at?->format('d M, Y'),
            'type' => $this->type,
            'helpdesk_category' => implode(', ', $this->helpdeskName->pluck('name')->toArray()),
            'helpdesk_category_id' => $this->helpdeskName->pluck('helpdesk_id'),
            'company' => collect([$this->company])->map(fn ($company) => [
                'picture' => asset($company->profile),
                'name' => $company->name,
                'username' => $company->username,
                'city' => $company->city,
                'address' => $company->address,
                'id' => $company->id,
            ])[0],
            'product_link' => Yellow::shareLink('product', $this->id),
            'is_favorite' => in_array($this->id, $myFavoriteProduct)
        ];
    }
}
