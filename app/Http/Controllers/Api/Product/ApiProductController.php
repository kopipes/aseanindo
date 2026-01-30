<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Helpdesk\ProductItemResource;
use App\Services\Helpdesk\CompanyProductService;
use App\Services\Util\BlockedAccountService;
use App\Services\Util\FavoriteProductService;
use App\Services\Util\ReportSpamService;
use Illuminate\Http\Request;

/**
 * @group Product
 */
class ApiProductController extends ApiController
{
    public function __construct(
        private CompanyProductService $companyProductService,
        private FavoriteProductService $favoriteProductService,
        private ReportSpamService $reportSpamService,
        private BlockedAccountService $blockedAccountService
    ) {
    }
    /**
     * List Product - Home
     * 
     * @authenticated
     * @defaultParam
     * 
     * @queryParam limit integer optional default 10
     * @queryParam page integer optional default 1
     * @queryParam search integer optional
     * @queryParam filter[category_id][0] string optional array list to filter product by company category
     * @queryParam filter[category_id][1] string optional array list to filter product by company category
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": [{
     *       "id": "99d45695-24cc-4cf6-82c0-8a21fdf5e37f",
     *       "image": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/products/4S9mYkBMxCVjLVhUlKozDcZpJTrBTIjBhzn4ZIqd.png",
     *       "name": "Good Moring",
     *       "description": "Fugit anim a eaque sint impedit nostrum voluptatem quis est et sit Digital Contact Center",
     *       "created_at": "2023-08-07T01:44:07.000000Z",
     *       "type": "inbound",
     *       "helpdesk_category": "Android Engginer, Jadwal, Aditama, Marketing, Testing",
     *       "helpdesk_category_id": [
     *           "99c0b8a4-5e0a-4bd6-8419-9e629c44b719",
     *           "99c0b9a1-782b-4523-b2c3-b2705b92aa42",
     *           "99c0df1b-c2d5-4a1f-bc27-c58b22e7e15a",
     *           "99c0b99d-9b0a-4477-9f63-dc617c4d95ac",
     *           "99ca741a-d45c-43d4-972f-711c66b3f22c"
     *       ],
     *       "company": {
     *           "profile": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/logo/qV46PK4dm8ugazzFpOhpJ6rXofxVC2e6FeD2YBJG.jpg",
     *           "name": "PT Taman Media Indonesia",
     *           "city": "Jakarta",
     *           "address": "Jl. Bina Remaja No.6, Srondol Wetan, Kec. Banyumanik, Kota Semarang, Jawa Tengah 50363",
     *           "id": "99c02fd2-7094-4064-a85f-e73bdf1cb50b"
     *       },
     *       "product_link": "https://haloyelow.com/customer/product/99d45695-24cc-4cf6-82c0-8a21fdf5e37f",
     *       "is_favorite": false
     *   }]
     * }
     */
    public function index(Request $request)
    {
        $userId = $this->user()?->id;
        $blockedBaId = $this->blockedAccountService->findAllBlockedCompany($userId);
        $products = $this->companyProductService->findAllProduct(
            blockedBaId: $blockedBaId,
            category: 'general',
            filter: $request->get('filter', []),
            search: $request->get('search', ''),
            limit: $request->get('limit', 10)
        );


        $request->merge([
            'FAVORITE_PRODUCT' => $this->favoriteProductService->findAllFavoriteProductId(
                $userId,
                collect($products->items())->pluck('id')->toArray()
            )
        ]);
        return $this->sendSuccess(ProductItemResource::collection($products));
    }

    /**
     * List Company Product
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required company id
     * 
     * 
     * @queryParam limit integer optional default 10
     * @queryParam page integer optional default 1
     * 
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": [{
     *       "id": "99d45695-24cc-4cf6-82c0-8a21fdf5e37f",
     *       "image": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/products/4S9mYkBMxCVjLVhUlKozDcZpJTrBTIjBhzn4ZIqd.png",
     *       "name": "Good Moring",
     *       "description": "Fugit anim a eaque sint impedit nostrum voluptatem quis est et sit Digital Contact Center",
     *       "created_at": "2023-08-07T01:44:07.000000Z",
     *       "type": "inbound",
     *       "helpdesk_category": "Android Engginer, Jadwal, Aditama, Marketing, Testing",
     *       "helpdesk_category_id": [
     *           "99c0b8a4-5e0a-4bd6-8419-9e629c44b719",
     *           "99c0b9a1-782b-4523-b2c3-b2705b92aa42",
     *           "99c0df1b-c2d5-4a1f-bc27-c58b22e7e15a",
     *           "99c0b99d-9b0a-4477-9f63-dc617c4d95ac",
     *           "99ca741a-d45c-43d4-972f-711c66b3f22c"
     *       ],
     *       "company": {
     *           "profile": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/logo/qV46PK4dm8ugazzFpOhpJ6rXofxVC2e6FeD2YBJG.jpg",
     *           "name": "PT Taman Media Indonesia",
     *           "city": "Jakarta",
     *           "address": "Jl. Bina Remaja No.6, Srondol Wetan, Kec. Banyumanik, Kota Semarang, Jawa Tengah 50363",
     *           "id": "99c02fd2-7094-4064-a85f-e73bdf1cb50b"
     *       },
     *       "product_link": "https://haloyelow.com/customer/product/99d45695-24cc-4cf6-82c0-8a21fdf5e37f",
     *       "is_favorite": false
     *   }]
     * }
     */
    public function productCompany(Request $request, $id)
    {
        $userId = $this->user()?->id;
        $products = $this->companyProductService->findAllProduct(
            blockedBaId: [],
            category: 'general',
            filter: [
                'company_id' => $id
            ],
            search: $request->get('search', ''),
            limit: $request->get('limit', 10)
        );


        $request->merge([
            'FAVORITE_PRODUCT' => $this->favoriteProductService->findAllFavoriteProductId(
                userId: $userId,
                productId: collect($products->items())->pluck('id')->toArray()
            )
        ]);
        return $this->sendSuccess(ProductItemResource::collection($products));
    }
    /**
     * Detail Product
     * 
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "Product not found"
     * }
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "id": "99d45695-24cc-4cf6-82c0-8a21fdf5e37f",
     *       "image": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/products/4S9mYkBMxCVjLVhUlKozDcZpJTrBTIjBhzn4ZIqd.png",
     *       "name": "Good Moring",
     *       "description": "Fugit anim a eaque sint impedit nostrum voluptatem quis est et sit Digital Contact Center",
     *       "created_at": "2023-08-07T01:44:07.000000Z",
     *       "type": "inbound",
     *       "helpdesk_category": "Android Engginer, Jadwal, Aditama, Marketing, Testing",
     *       "helpdesk_category_id": [
     *           "99c0b8a4-5e0a-4bd6-8419-9e629c44b719",
     *           "99c0b9a1-782b-4523-b2c3-b2705b92aa42",
     *           "99c0df1b-c2d5-4a1f-bc27-c58b22e7e15a",
     *           "99c0b99d-9b0a-4477-9f63-dc617c4d95ac",
     *           "99ca741a-d45c-43d4-972f-711c66b3f22c"
     *       ],
     *       "company": {
     *          "picture": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/logo/qV46PK4dm8ugazzFpOhpJ6rXofxVC2e6FeD2YBJG.jpg",
     *          "name": "PT Taman Media Indonesia",
     *          "city": "Jakarta",
     *          "address": "Jl. Bina Remaja No.6, Srondol Wetan, Kec. Banyumanik, Kota Semarang, Jawa Tengah 50363",
     *          "id": "99c02fd2-7094-4064-a85f-e73bdf1cb50b"
     *       },
     *       "product_link": "https://haloyelow.com/customer/product/99d45695-24cc-4cf6-82c0-8a21fdf5e37f",
     *       "is_favorite": false
     *   }
     * }
     */
    public function show(Request $request, $id)
    {
        if (!$product = $this->companyProductService->findProductById($id)) {
            return $this->badRequest('Product not found');
        }
        $userId = $this->user()?->id;
        $request->merge([
            'FAVORITE_PRODUCT' => $this->favoriteProductService->findAllFavoriteProductId($userId, [$id])
        ]);
        return $this->sendSuccess(new ProductItemResource($product));
    }

    /**
     * Report Product
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required product id
     * 
     * @requestBody multipart/form-data
     * @bodyParam reason string required
     * 
     * @response {
     *   "status": 200,
     *   "message": "Report successfully"
     * }
     * 
     * @response 400 {
     *   "status": 400,
     *   "error": "bad_request",
     *   "message": "You have reported before!"
     * }
     */
    public function report(Request $request, $id)
    {
        $this->validates(['reason' => 'required']);
        $userId = $this->user()?->id;
        $type = 'product';
        if (!$this->reportSpamService->findReport($id, $type, $userId)) {
            $this->reportSpamService->reportContent(
                accountId: $id,
                accountType: $type,
                reportBy: $userId,
                reason: $request->reason
            );

            return $this->sendMessage('Report successfully');
        }
        return $this->badRequest('You have reported before!');
    }


    /**
     * List Product - Following
     * 
     * @authenticated
     * @defaultParam
     * 
     * @queryParam limit integer optional default 10
     * @queryParam page integer optional default 1
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": [{
     *       "id": "99d45695-24cc-4cf6-82c0-8a21fdf5e37f",
     *       "image": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/products/4S9mYkBMxCVjLVhUlKozDcZpJTrBTIjBhzn4ZIqd.png",
     *       "name": "Good Moring",
     *       "description": "Fugit anim a eaque sint impedit nostrum voluptatem quis est et sit Digital Contact Center",
     *       "created_at": "2023-08-07T01:44:07.000000Z",
     *       "type": "inbound",
     *       "helpdesk_category": "Android Engginer, Jadwal, Aditama, Marketing, Testing",
     *       "helpdesk_category_id": [
     *           "99c0b8a4-5e0a-4bd6-8419-9e629c44b719",
     *           "99c0b9a1-782b-4523-b2c3-b2705b92aa42",
     *           "99c0df1b-c2d5-4a1f-bc27-c58b22e7e15a",
     *           "99c0b99d-9b0a-4477-9f63-dc617c4d95ac",
     *           "99ca741a-d45c-43d4-972f-711c66b3f22c"
     *       ],
     *       "company": {
     *           "profile": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/logo/qV46PK4dm8ugazzFpOhpJ6rXofxVC2e6FeD2YBJG.jpg",
     *           "name": "PT Taman Media Indonesia",
     *           "city": "Jakarta",
     *           "address": "Jl. Bina Remaja No.6, Srondol Wetan, Kec. Banyumanik, Kota Semarang, Jawa Tengah 50363",
     *           "id": "99c02fd2-7094-4064-a85f-e73bdf1cb50b"
     *       },
     *       "product_link": "https://haloyelow.com/customer/product/99d45695-24cc-4cf6-82c0-8a21fdf5e37f",
     *       "is_favorite": false
     *   }]
     * }
     */
    public function followingProduct(Request $request)
    {
        $userId = $this->user()?->id;
        $products = $this->companyProductService->findAllFollowingProduct(
            customerId: $userId,
            limit: $request->get('limit', 10)
        );

        $request->merge([
            'FAVORITE_PRODUCT' => $this->favoriteProductService->findAllFavoriteProductId(
                userId: $userId,
                productId: collect($products->items())->pluck('id')->toArray()
            )
        ]);
        return $this->sendSuccess(ProductItemResource::collection($products));
    }


    /**
     * List Product - Favorite
     * 
     * @authenticated
     * @defaultParam
     * 
     * @queryParam limit integer optional default 10
     * @queryParam page integer optional default 1
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": [{
     *       "id": "99d45695-24cc-4cf6-82c0-8a21fdf5e37f",
     *       "image": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/products/4S9mYkBMxCVjLVhUlKozDcZpJTrBTIjBhzn4ZIqd.png",
     *       "name": "Good Moring",
     *       "description": "Fugit anim a eaque sint impedit nostrum voluptatem quis est et sit Digital Contact Center",
     *       "created_at": "2023-08-07T01:44:07.000000Z",
     *       "type": "inbound",
     *       "helpdesk_category": "Android Engginer, Jadwal, Aditama, Marketing, Testing",
     *       "helpdesk_category_id": [
     *           "99c0b8a4-5e0a-4bd6-8419-9e629c44b719",
     *           "99c0b9a1-782b-4523-b2c3-b2705b92aa42",
     *           "99c0df1b-c2d5-4a1f-bc27-c58b22e7e15a",
     *           "99c0b99d-9b0a-4477-9f63-dc617c4d95ac",
     *           "99ca741a-d45c-43d4-972f-711c66b3f22c"
     *       ],
     *       "company": {
     *           "profile": "http://proyek.test/yelow-premium/callnchat-and-api/public/uploads/logo/qV46PK4dm8ugazzFpOhpJ6rXofxVC2e6FeD2YBJG.jpg",
     *           "name": "PT Taman Media Indonesia",
     *           "city": "Jakarta",
     *           "address": "Jl. Bina Remaja No.6, Srondol Wetan, Kec. Banyumanik, Kota Semarang, Jawa Tengah 50363",
     *           "id": "99c02fd2-7094-4064-a85f-e73bdf1cb50b"
     *       },
     *       "product_link": "https://haloyelow.com/customer/product/99d45695-24cc-4cf6-82c0-8a21fdf5e37f",
     *       "is_favorite": false
     *   }]
     * }
     */
    public function favoriteProduct(Request $request)
    {
        $userId = $this->user()?->id;
        $products = $this->companyProductService->findAllFavoriteProductCustomer(
            customerId: $userId,
            limit: $request->get('limit', 10)
        );

        $request->merge([
            'FAVORITE_PRODUCT' => collect($products->items())->pluck('id')->toArray()
        ]);
        return $this->sendSuccess(ProductItemResource::collection($products));
    }


    /**
     * Love UnLove (Favorite)
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam product_id string required
     * @pathParam company_id string required
     * 
     * @response {
     *   "status": 200,
     *   "message": "Success"
     * }
     */
    public function love(Request $request, $productId, $companyId)
    {
        $this->favoriteProductService->createOrDelete(
            customerId: $this->user()?->id,
            companyId: $companyId,
            productId: $productId
        );

        return $this->sendMessage('Success');
    }
}
