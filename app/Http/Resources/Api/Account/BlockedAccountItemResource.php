<?php

namespace App\Http\Resources\Api\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlockedAccountItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $picture = $this->type == 'customer' ? $this->profile : $this->logo;
        return [
            'id' => $this->id,
            'type' => $this->type,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'name' => $this->type == 'customer' ? $this->customer_name : $this->company_name,
            'picture' => asset($picture ?: config('services.placeholder_avatar')),
        ];
    }
}
