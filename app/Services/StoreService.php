<?php 

namespace App\Services;

use App\Http\Resources\StoreResource;
use App\Repositories\StoreRepository;
use Illuminate\Http\Request;
use App\Jobs\InsertViewsJob;
use App\Validators\StoreValidator;
use Illuminate\Support\Facades\Auth;

class StoreService {
    //can also implemented with enums
    private $allowedSortBy = [
        "title",
        "description",
        "created_at",
        "views",
        "positive_reviews",
        "negative_reviews"
    ];

    private $allowedOrderBy = [
        "ASC",
        "DESC"
    ];

    public function __construct(
        private readonly OutputService $outputService,
        private readonly StoreRepository $storeRepository
    ) {}

    public function validateSearchCriteria($request) {
        return StoreValidator::validateSearchInput($request);
    } 

    public function getStores(Request $request) {
        $request->merge(["isSingular" => false]);
    
        $requestSortBy = strtolower($request->sortBy);
        $sortBy = in_array($requestSortBy, $this->allowedSortBy) ? $requestSortBy : null;

        $requestOrder = strtoupper($request->order);
        $order = in_array($requestOrder, $this->allowedOrderBy) ? $requestOrder : null;

        $stores = $this->storeRepository->getStoresPaginated($sortBy, $order, $request->limit, $request->q);
        $this->managerViewsCounting(Auth::guard("api")->user(), $stores->map(fn($el) => $el->id)->toArray());

        return $this->outputService->handlePaginatedOutput(
            $stores,
            StoreResource::class
        );
    }

    public function getSingleStore(Request $request, $storeId) {
        $request->merge(["isSingular" => true]);
        $store = $this->storeRepository->getSingleStore($storeId);
        if(!$store) return null;
        $this->managerViewsCounting(Auth::guard("api")->user(), $store->id);
        return $store ? StoreResource::make($store) : null;
    }

    public function managerViewsCounting($user, $storeIds) {
        if (!$user) return;
        $stores = !is_array($storeIds) ? [$storeIds] : $storeIds;
        InsertViewsJob::dispatch($user->id, $stores);
    }
}