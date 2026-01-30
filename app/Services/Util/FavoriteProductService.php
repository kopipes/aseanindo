<?php

namespace App\Services\Util;

use App\Models\Util\FavoriteProduct;

class FavoriteProductService
{
     public function __construct(
          public $model = FavoriteProduct::class
     ) {
     }

     public function findAllFavoriteProductId($userId, $productId = [])
     {
          return $userId
               ? $this->model::where('user_id', $userId)
               ->whereIn('company_product_id', $productId)
               ->distinct()
               ->pluck('company_product_id')
               ->toArray()
               : [];
     }

     public function findAllProductId($userId)
     {
          return $this->model::where('user_id', $userId)
               ->distinct()
               ->pluck('company_product_id')
               ->toArray();
     }

     public function removeAllFavoriteProduct($userId, $companyId)
     {
          return $this->model::where('company_id', $companyId)
               ->where('user_id', $userId)
               ->delete();
     }

     public function createOrDelete($customerId, $companyId, $productId)
     {
          $exist = $this->model::where('company_id', $companyId)
               ->where('company_product_id', $productId)
               ->where('user_id', $customerId)
               ->first();
          if (!$exist) {
               $this->model::create([
                    'company_id' => $companyId,
                    'company_product_id' => $productId,
                    'user_id' => $customerId,
               ]);
          } else {
               $exist->delete();
          }
     }
}
