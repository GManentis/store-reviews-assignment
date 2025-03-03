<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Stores and reviews API",
 *     version="1.0",
 *     description="This is the API documentation for storing reviews per stores."
 * )
 * @OA\Server(
 *     url="http://localhost",
 *     description="Local Server"
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Token based Based",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="apiAuth",
 * )
 * @OA\Components(
 *     @OA\Schema(
 *         schema="login",
 *         type="object",
 *         required={"email", "password"},
 *         @OA\Property(property="email", type="string", format="email", description="User's email"),
 *         @OA\Property(property="password", type="string", description="User's password")
 *     ),
 *     @OA\Schema(
 *         schema="register",
 *         type="object",
 *         required={"name", "email", "password"},
 *         @OA\Property(property="name", type="string", description="User's name"),
 *         @OA\Property(property="email", type="string", format="email", description="User's email"),
 *         @OA\Property(property="password", type="string", description="User's password")
 *     ),
 *     @OA\Schema(
 *         schema="reviews",
 *         type="object",
 *         required={"review"},
 *         @OA\Property(property="review", type="number", description="The review for store. 1 is for positive review, 0 is for negative")
 *     ),
 * )
 */
abstract class Controller
{
    //
}
