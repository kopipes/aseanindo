<?php

namespace App\Models\Data;

use App\Models\Data\TicketScheduleDetail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'tickets';

    protected $guarded = [];

    protected $casts = [
        'sla_resolution_time' => 'array',
        'sla_response_time' => 'array',
        'sla_division' => 'array'
    ];

    public function bookingDetails()
    {
        return $this->hasMany(TicketScheduleDetail::class, 'ticket_id', 'id')
        ->leftJoin('products_schedule_detail','products_schedule_detail.id','ticket_schedule_detail.product_schedule_detail_id')
        ->select([
            'ticket_schedule_detail.queue_number',
            'ticket_schedule_detail.counseling_date',
            'ticket_schedule_detail.number',
            'ticket_schedule_detail.name','products_schedule_detail.start_date',
            'products_schedule_detail.end_date','products_schedule_detail.start_time',
            'products_schedule_detail.end_time',
            'ticket_schedule_detail.ticket_id',
            'ticket_schedule_detail.counseling_time',
        ])
        ->orderBy('ticket_schedule_detail.number', 'asc');
    }
    public function product()
    {
        return $this->hasOne(CompanyProduct::class, 'id', 'product_id');
    }

}
