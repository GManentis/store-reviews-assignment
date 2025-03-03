<?php

namespace App\Http\Controllers;

use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        private readonly ReviewService $reviewService 
    ) {}
    /**
     * @OA\Post(
     *     path="/api/stores/{storeId}/reviews",
     *     summary="Create existing review for a store",
     *     tags={"Reviews"},
     *     security={{"apiAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/reviews")
     *     ),
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
     *         description="Review for store is created"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="The review is empty or not 0 or 1"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not authenticated in the first place"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="The store or review for this user does not exist"
     *     )
     * )
    */
    public function create(Request $request) {
        $error = $this->reviewService->checkReviewNewAndInput($request);
        if ($error) return response()->json(["message" => $error->message], $error->status);
        $this->reviewService->insertNewReviewStore($request);
        return response()->json(["message" => "Η αξιολόγηση για το κατάστημα καταχωρήθηκε επιτυχώς"]);
    }

    /**
     * @OA\Put(
     *     path="/api/stores/{storeId}/reviews",
     *     summary="Update existing review for a store",
     *     tags={"Reviews"},
     *     security={{"apiAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/reviews")
     *     ),
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
     *         description="Review for store is updated"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="The review is empty or not 0 or 1"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not authenticated in the first place"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="The store or review for this user does not exist"
     *     )
     * )
    */
    public function update(Request $request) {
        $error = $this->reviewService->checkExistingReviewAndInput($request);
        if ($error) return response()->json(["message" => $error->message], $error->status);
        $this->reviewService->updateReview($request);
        return response()->json(["message" => "Η αξιολόγηση για το κατάστημα ενημερώθηκε επιτυχώς"]);
    }

    /**
     * @OA\Delete(
     *     path="/api/stores/{storeId}/reviews",
     *     summary="Update existing review for a store",
     *     tags={"Reviews"},
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
     *         description="Selected review for store is deleted"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not authenticated in the first place"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="The store or review for this user does not exist"
     *     )
     * )
    */
    public function destroy(Request $request) {
        if (!$this->reviewService->checkIfReviewExists($request->user(), $request->store->id)) return response()->json(["message" => "Δεν εχετε ορίσει κριτική για το κατάστημα"], 404); 
        $this->reviewService->removeReview($request);
        return response()->json(["message" => "Η αξιολογηση σας διαγράφτηκε επιτυχώς"]);
    }
}
