<?php

namespace App\Http\Resources\Api\Auth;

use App\Helpers\Crypto;
use App\Helpers\JwtToken;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginWithTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $token = JwtToken::setData([
            'id' => Crypto::encrypt($this->id),
            'permission' => 'customer',
            'regid' => $request->header('regid'),
            'scope' => 'auth'
        ])
            ->setExpired("+2 days")
            ->build();

        return [
            'code' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile' => asset($this->profile),
            'phone' => $this->phone,
            'role' => 'customer',
            'lang' => $this->lang ?: 'en',
            'username' => $this->username,
            'request_delete_account_at' => null,
            'token' => $token
        ];
    }
}
