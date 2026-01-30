<?php

namespace App\Http\Resources\Api\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatHistoryItemResource extends JsonResource
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
        return [
            'id' => $this->id,
            'status' => $this->status,
            'company_id' => $this->company_id,
            'company_name' => $this->company_name,
            'last_message' => $this->last_message,
            'date' => $this->end_at ?: $this->start_at,
            'agent_id' => $agent->id,
            'agent_profile' => asset($agent->profile),
            'agent_name' => $agent->name,
            'helpdesk_category' => $agent->helpdesk ? implode(", ",json_decode($agent->helpdesk)) : null,
        ];
    }
}
