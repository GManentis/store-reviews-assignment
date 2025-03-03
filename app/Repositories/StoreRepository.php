<?php

namespace App\Repositories;

use App\Models\Store;

class StoreRepository {

    public function getBasicQuery() {
        return Store::select("stores.id","stores.title","stores.description","stores.created_at")
        ->selectRaw("COUNT(CASE WHEN reviews.review = 1 THEN 1 END) AS positive_reviews")
        ->selectRaw("COUNT(CASE WHEN reviews.review = 0 THEN 1 END) AS negative_reviews")
        ->selectRaw("COUNT(views.id) AS views")
        ->leftJoin("reviews", 'stores.id', '=', 'reviews.store_id')
        ->leftJoin("views", "stores.id", "=", "views.store_id")
        ->groupBy("stores.id");
    }

    public function getStoresPaginated($sortBy, $order, $limit, $searchParam) {
        $limit = is_numeric($limit) && $limit > 0 && $limit % 1 == 0 ? $limit : 10;
        $queryForStoreEntries = $this->getBasicQuery();

        if (!$sortBy) $sortBy = "created_at";

        if ($searchParam) {
            $queryForStoreEntries = $queryForStoreEntries->where(function ($query) use ($searchParam) {
                $query->where("title", "like", "%$searchParam%")->orWhere("description", "like", "%$searchParam%");
            });
        }

        return $queryForStoreEntries->orderBy($sortBy, $order)->orderBy("stores.id", "ASC")->paginate($limit)->appends(['limit' => $limit]);
    }

    public function getSingleStore($storeId) {
        return $this->getBasicQuery()->where("stores.id", $storeId)->first();
    }

}


