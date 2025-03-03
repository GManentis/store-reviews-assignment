<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login endpoint",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/login")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Failed to authenticate user"
     *     ),
     * )
    */
    public function login(Request $request) {
            $criteria = [
                'email' => 'required|max:255|email',
                'password' => 'required|max:255'
            ];

            $validator = Validator::make($request->all(), $criteria);
            if ($validator->fails()) return response()->json(['message' => $validator->errors()->first()], 400);

            $email = $request->email;
            $password = $request->password;

            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                $user = Auth::user();
                $token = $user->createToken('appToken')->accessToken;

                return response()->json(['success' => true, 'token' => $token, 'user' => $user], 200);
            } else {
                // failure to authenticate
                return response()->json(['success' => false, 'message' => 'Failed to authenticate.'], 401);
            }
    }


    /**
     * @OA\Get(
     *     path="/api/logout",
     *     summary="Logout endpoint",
     *     tags={"Users"},
     *     security={{"apiAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User logout successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="User not authenticated in the first place"
     *     )
     * )
    */
    public function logout(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();
        // Revoke the current access token
        $user->token()->revoke();
        return response()->json(['message' => 'Logged out successfully']);
    }


    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register endpoint",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/register")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
    */
    public function register(Request $request) {
        $criteria = [
            'name' => 'required|max:255',
            'email'=>'required|unique:users|max:255|email',
            'password'=> 'required|max:255|min:10'
        ];


        $validator = Validator::make($request->all(), $criteria);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->first()], 400);
        }


        $input = [];
        foreach (array_keys($criteria) as $crit) $input[$crit] = $request->{$crit};

        User::create($input);

        return response()->json(['message' => "Το προφιλ δημιουργήθηκε επιτυχώς"]);
    }
}
