<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StoreService;

class StoreController extends Controller
{
    public function __construct(
        private readonly StoreService $storeService
    ) {}


    /**
     * @OA\Get(
     *     path="/api/stores",
     *     summary="Get all stores paginated",
     *     tags={"Stores"},
     *     security={{"apiAuth": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=""
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sortBy",
     *         in="query",
     *         description="Criteria for sortBy. Can be title, description, created_at, views, positive_reviews, negative_reviews",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=""
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="If the results are going to be in ASC or DESC order according to sortBy",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=""
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of records per page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=""
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="param according to which the search is performed. Must not exceed 100 characters",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example=""
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="location categories are returned"
     *     ),
     * )
    */
    public function index(Request $request) {
        $error = $this->storeService->validateSearchCriteria($request);
        if ($error) return response()->json(["message" => $error], 400);
        return response()->json($this->storeService->getStores($request));
    }

    /**
     * @OA\Get(
     *     path="/api/stores/{storeId}",
     *     summary="Get store by id",
     *     tags={"Stores"},
     *     security={{"apiAuth": {}}},
     *     @OA\Parameter(
     *         name="storeId",
     *         in="path",
     *         required=true,
     *         description="Wildcard path parameter for store id",
     *         @OA\Schema(
     *             type="string",
     *             example="500"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The selected store is returned"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="The store does not exist"
     *     ),
     * )
    */
    public function show(Request $request, $storeId) {
        $store = $this->storeService->getSingleStore($request, $storeId);
        return response()->json($store ?? ["message" => "Store is not available"], $store ? 200 : 404); 
    }
}
