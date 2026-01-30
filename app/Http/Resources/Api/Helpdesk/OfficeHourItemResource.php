<?php

namespace App\Http\Resources\Api\Helpdesk;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfficeHourItemResource extends JsonResource
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
            'sunday' => $this->sunday ?  (@$this->sunday['start'] ?: '00:00') . ' - ' . (@$this->sunday['end'] ?: '23:59') : 'Closed',
            'monday' => $this->monday ?  (@$this->monday['start'] ?: '00:00') . ' - ' . (@$this->monday['end'] ?: '23:59') : 'Closed',
            'tuesday' => $this->tuesday ?  (@$this->tuesday['start'] ?: '00:00') . ' - ' . (@$this->tuesday['end'] ?: '23:59') : 'Closed',
            'wednesday' => $this->wednesday ?  (@$this->wednesday['start'] ?: '00:00') . ' - ' . (@$this->wednesday['end'] ?: '23:59') : 'Closed',
            'thursday' => $this->thursday ?  (@$this->thursday['start'] ?: '00:00') . ' - ' . (@$this->thursday['end'] ?: '23:59') : 'Closed',
            'friday' => $this->friday ?  (@$this->friday['start'] ?: '00:00') . ' - ' . (@$this->friday['end'] ?: '23:59') : 'Closed',
            'saturday' => $this->saturday ?  (@$this->saturday['start'] ?: '00:00') . ' - ' . (@$this->saturday['end'] ?: '23:59') : 'Closed',
        ];
    }
}
