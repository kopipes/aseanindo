<?php

namespace App\Services\Util;

use App\Models\Data\Notification;

class NotificationService
{
     public function __construct(
          public $model = Notification::class
     ) {
     }

     public function findAllCallHistory($customerId, $limit)
     {
          return $this->model::join('companies', 'companies.id', 'notifications.company_id')
               ->where('notifications.user_id', $customerId)
               ->where('notifications.user_role', 'customer')
               ->where('companies.status', 'approved')
               ->where('notifications.category', 'call_history')
               ->select([
                    'notifications.id', 'notifications.company_id', 'companies.logo as picture',
                    'companies.name', 'notifications.created_at', 'notifications.type', 'notifications.title', 'notifications.description'
               ])
               ->latest('notifications.created_at')
               ->paginate($limit ?: 10);
     }

     public function findAllNotification($customerId, $search, $limit)
     {
          return $this->model::join('companies', 'companies.id', 'notifications.company_id')
               ->where('notifications.user_id', $customerId)
               ->where('notifications.user_role', 'customer')
               ->where('companies.status', 'approved')
               ->where('notifications.category', 'notification')
               ->when($search, function ($query) use ($search) {
                    $query->where('notifications.title', 'like', "%{$search}%");
                    $query->orWhere('notifications.description', 'like', "%{$search}%");
               })
               ->select([
                    'notifications.id', 'notifications.company_id', 'companies.logo as picture', 'notifications.created_at', 'notifications.parent_id',
                    'companies.name', 'notifications.created_at', 'notifications.type', 'notifications.title', 'notifications.description', 'notifications.details',
                    'notifications.is_read'
               ])
               ->latest('notifications.created_at')
               ->paginate($limit ?: 10);
     }

     public function countAllNotification($customerId)
     {
          return $this->model::join('companies', 'companies.id', 'notifications.company_id')
               ->where('notifications.user_id', $customerId)
               ->where('notifications.user_role', 'customer')
               ->where('companies.status', 'approved')
               ->where('notifications.category', 'notification')
               ->where('notifications.is_read', 0)
               ->count();
     }

     public function readNotification($customerId, $id)
     {
          return $this->model::where('user_id', $customerId)
               ->where('id', $id)
               ->where('user_role', 'customer')
               ->update(['is_read' => 1]);
     }

     public function deleteNotification($customerId, $id)
     {
          return $this->model::where('user_id', $customerId)
               ->where('id', $id)
               ->where('user_role', 'customer')
               ->delete();
     }



     public function updateDetailRatingNotification($customerId, $ratingId, $rating)
     {
          return $this->model::where('user_id', $customerId)
               ->where('user_role', 'customer')
               ->where('category', 'notification')
               ->where('parent_id', $ratingId)
               ->where('type', 'like', '%rating_%')
               ->update([
                    'details' => ['rating' => $rating]
               ]);
     }
}
