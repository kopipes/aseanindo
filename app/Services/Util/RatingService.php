<?php

namespace App\Services\Util;

use App\Models\Data\Rating;

class RatingService
{
     public function __construct(
          public $model = Rating::class
     ) {
     }

     public function findAllCompanyRating($companyId, $limit = 10)
     {
          return $this->model::join('users', 'users.id', 'ratings.users_id')
               ->where('ratings.company_id', $companyId)
               ->where('ratings.is_deleted', 0)
               ->whereNotNUll('ratings.rating')
               ->select([
                    'ratings.id', 'users.name', 'users.profile', 'users.email', 'users.phone',
                    'ratings.rating', 'ratings.review', 'users.username'
               ])
               ->orderBy('ratings.rating', 'desc')
               ->paginate($limit ?: 10)
               ->each(fn ($user) => $user->profile = asset($user->profile));
     }

     public function findDetailRating($ratingId, $customerId)
     {
          return $this->model::join('users as agent', 'agent.id', 'ratings.agent_id')
               ->join('companies', 'companies.id', 'ratings.company_id')
               ->join('tickets', 'tickets.id', 'ratings.ticket_id')
               ->leftJoin('company_products as product', 'product.id', 'ratings.product_id')
               ->leftJoin('products_schedule_detail as schedule', 'schedule.product_id', 'product.id')
               ->leftJoin('users as doctor', 'doctor.id', 'schedule.pic_id')
               ->where('ratings.users_id', $customerId)
               ->where('ratings.id', $ratingId)
               ->select([
                    'ratings.id', 'companies.name as company_name', 'companies.logo as company_image', 'product.name as nama_product',
                    'product.image as image_product', 'agent.name as agent_name', 'doctor.name as doctor_name', 'doctor.profile as doctor_image',
                    'schedule.professional_type', 'tickets.ticket_number', 'tickets.note', 'product.category as product_category', 'ratings.booking_detail',
                    'ratings.rating', 'ratings.review', 'companies.id as company_id', 'ratings.category', 'ratings.product_name', 'ratings.product_type',
                    'ratings.product_image', 'ratings.product_id', 'ratings.created_at'
               ])
               ->first();
     }

     public function updateRating($ratingId, $customerId, $properties)
     {
          return $this->model::where('users_id', $customerId)
               ->where('id', $ratingId)
               ->update($properties);
     }

     public function updateRatingByTicket($ticket, $customerId, $properties)
     {
          $rating = $this->model::where('users_id', $customerId)
               ->where('ticket_id', $ticket->id)
               ->where('agent_id', $ticket->agent_id)
               ->where('company_id', $ticket->company_id)
               ->select(['id'])
               ->firstOrFail();
          $rating->update($properties);
          return $rating;
     }
}
