<?php

namespace App\Services;

use App\Validators\ReviewValidator;

class ReviewService {

    public function checkReviewNewAndInput($request) {
        //$review = $request->user()->reviews()->wherePivot("store_id", $storeId)->first();
        $review = $this->checkIfReviewExists($request->user(), $request->store->id);
        if ($review) return (object)["message" => "Έχετε ορίσει ήδη κριτική για το κατάστημα", "status" => 400]; 

        $error = $this->validateReviewInput($request);
        if ($error) return (object)["message" => $error, "status" => 400];
        return null;
    }

    public function insertNewReviewStore($request) {
        $request->user()->reviews()->attach([$request->store->id => ["review" => $request->review]]);
    }

    public function updateReview($request) {
        $request->user()->reviews()->updateExistingPivot($request->store->id, ["review" => $request->review]);
    }

    public function removeReview($request) {
        $request->user()->reviews()->detach([$request->store->id]);
    }

    public function validateReviewInput($request) {
        $error = ReviewValidator::validateInput($request);
        return $error ?? null;
    }

    public function checkExistingReviewAndInput($request) {
        $review = $this->checkIfReviewExists($request->user(), $request->store->id);
        if (!$review) return (object)["message" => "Δεν εχετε ορίσει κριτική για το κατάστημα", "status" => 404]; 

        $error = $this->validateReviewInput($request);
        if ($error) return (object)["message" => $error, "status" => 400];
        return null;
    }

    public function checkIfReviewExists($user, $storeId) {
        return $user->reviews()->wherePivot("store_id", $storeId)->first();
    }

}