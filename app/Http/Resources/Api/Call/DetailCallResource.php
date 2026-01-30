<?php

namespace App\Http\Resources\Api\Call;

use App\Helpers\Agora\Agora;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailCallResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $agent = $this->agent;
        $agent->profile = asset($this->profile_agent ?: config('services.placeholder_avatar'));
        return [
            'call_id' => $this->id,
            'status' => $this->status,
            'channel_name' => $this->channel_name,
            'agent_token' => $this->agent_token,
            'customer_token' => $this->customer_token,
            'agent_uuid' => Agora::createUserId($this->agent_id),
            'customer_uuid' => Agora::createUserId($this->customer_id),
            'call_type' => $this->category,
            'start_at' => date('Y-m-d H:i:s', strtotime($this->start_at)),
            'company_id' => $this->company_id,
            'is_sip' => $this->sip ? true : false,
            'spv_join_at' => $this->spv_join_at ? date('Y-m-d H:i:s', strtotime($this->spv_join_at)) : null,
            'spv' => !$this->spv_join_at ? null : [
                'id' => $this->spv_id,
                'name' => $this->spv_name,
                'email' => $this->spv_email,
                'phone' => $this->spv_phone,
                'profile' => $this->profile_spv,
            ],
            'agent' => $agent,
        ];
    }
}
