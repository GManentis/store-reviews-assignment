<?php 

namespace App\Validators; 

use Illuminate\Support\Facades\Validator;

class ReviewValidator {

    private static $criteria = [
        "review" => "required|in:0,1"
    ];

    public static function validateInput($request) {
        $validator = Validator::make($request->all(), self::$criteria);
        if($validator->fails()) return $validator->errors()->first();
        return null;
    }
}

