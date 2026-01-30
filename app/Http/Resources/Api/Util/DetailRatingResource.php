<?php

namespace App\Http\Resources\Api\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailRatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'company' => [
                'id' => $this->company_id,
                'name' => $this->company_name,
                'image' => asset($this->company_image),
            ],
            'category' => $this->category,
            'date' => date('d M Y',strtotime($this->created_at)),
            'time' => date('H:i A',strtotime($this->created_at)),
            'created_date' => date('dd, D M Y H:i:s',strtotime($this->created_at)),
            'agent' => $this->agent_name,
            'note' => $this->note,
            'ticket_number' => $this->ticket_number,
            'product' => [
                'id' => $this->product_id,
                'image' => asset($this->image_product ?: $this->product_image),
                'name' => $this->nama_product ?: $this->product_name,
                'type' => $this->product_category ?: $this->product_type ,
                'doctor_image' => $this->doctor_image ? asset($this->doctor_image) : null,
                'doctor_name' => $this->doctor_name,
                'medical_specialist' => $this->professional_type,
            ],
            'booking_details' => $this->booking_detail ? json_decode($this->booking_detail) : null,
            'rating' => $this->rating,
            'review' => $this->review,
            'schedule_type' => $this->product_category == 'schedule_professional' ? 'doctor' : 'other',
            'category_status' => null,
        ];
    }
}
