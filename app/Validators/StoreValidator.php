<?php 

namespace App\Validators; 

use Illuminate\Support\Facades\Validator;

class StoreValidator {
    private static $searchCriteria = [
        "q" => "nullable|max:100"
    ];

    public static function validateSearchInput($request) {
        $validator = Validator::make($request->all(), self::$searchCriteria);
        if ($validator->fails()) return $validator->errors()->first();
        return null;
    }
}

