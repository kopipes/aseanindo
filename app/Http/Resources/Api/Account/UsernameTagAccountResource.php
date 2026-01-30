<?php
namespace App\Http\Resources\Api\Account;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsernameTagAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'picture' => asset($this->picture ?: config('services.placeholder_avatar')),
            'tag' => '<a class="username_tag" data-id="'.$this->id.'" data-type="'.$this->type.'">@'.$this->username.'</a>',
        ];
    }
}
