<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Validators\ReviewValidator;

class ReviewControllerOLD extends Controller
{
    public function create(Request $request) {
        $review = $request->user()->reviews()->wherePivot("store_id", $request->store->id)->first();
        if ($review) return response()->json(["message" => "Έχετε ορίσει ήδη κριτική για το κατάστημα"], 400); 

        $error = ReviewValidator::validateInput($request);
        if($error) return response()->json(["message" => $error]);

        $request->user()->reviews()->attach([$request->store->id => ["review" => $request->review]]);
        return response()->json(["message" => "Η αξιολόγηση για το κατάστημα καταχωρήθηκε επιτυχώς"]);
    }

    public function update(Request $request) {
        $review = $request->user()->reviews()->wherePivot("store_id", $request->store->id)->first();
        if (!$review) return response()->json(["message" => "Δεν εχετε ορίσει κριτική για το κατάστημα"], 400); 

        $request->user()->reviews()->updateExistingPivot($request->store->id, ["review" => $request->review]);
        return response()->json(["message" => "Η αξιολόγηση για το κατάστημα ενημερώθηκε επιτυχώς"]);
    }

    public function destroy(Request $request) {
        $review = $request->user()->reviews()->wherePivot("store_id", $request->store->id)->first();
        if (!$review) return response()->json(["message" => "Δεν εχετε ορίσει κριτική για το κατάστημα"], 400); 

        $request->user()->reviews()->detach([$request->store->id]);
        return response()->json(["message" => "Η αξιολογηση σας διαγράφτηκε επιτυχώς"]);
    }
}
