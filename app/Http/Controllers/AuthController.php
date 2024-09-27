<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\AuthInterface;

/**
 * @OA\Info(
 *     title="Authentication API",
 *     version="1.0.0",
 *     description="API for user authentication including login and registration.",
 *
 *     @OA\Contact(
 *         name="Your Name",
 *         email="your-email@example.com"
 *     )
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="User",
     *
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="John Doe"),
     *     @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *     @OA\Property(property="user_type", type="string", example="user"),
     *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-27T12:00:00Z"),
     *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-27T12:00:00Z")
     * )
     */
    protected $auth;

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="User login",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="your_password")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="token", type="string", example="your_jwt_token"),
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid credentials",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Invalid email or password.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity - Email not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Email not found.")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        return $this->auth->login($request->validated());
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Authentication"},
     *     summary="User registration",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="your_password")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Registration successful",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="token", type="string", example="your_jwt_token"),
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity - Validation Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="The email has already been taken."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array",
     *
     *                     @OA\Items(type="string", example="The email has already been taken.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        return $this->auth->register($request->validated());
    }
}
