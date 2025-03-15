<?php

namespace App\Http\Controllers\Api\Public\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use OpenApi\Annotations as OA;
class AuthController extends Controller implements HasMiddleware
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // 🔹 Ensure middleware() is defined AFTER the constructor
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['login'])
        ];
    }

    /*
    ** login method
    */
/**
 * @OA\Post(
 *     path="/auth/login",
 *     summary="Authenticate user and generate JWT token",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username", "password"},
 *             @OA\Property(property="username", type="string", example="admin"),
 *             @OA\Property(property="password", type="string", example="MaNs123456")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="access_token", type="string", example="your_jwt_token")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid credentials"
 *     )
 * )
 */

    public function login(LoginRequest $loginReq)
    {
        return $this->authService->login($loginReq->validated());
    }

    /*
    ** logout method
    */

    /**
     *
     * @OA\Post(
         *     path="/api/v1/auth/logout",
         *     summary="Logout user",
         *     tags={"Authentication"},
         *     @OA\Response(
             *         response=200,
             *         description="Logout successful",
             *         @OA\JsonContent(
                 *             @OA\Property(property="message", type="string", example="You have logged out")
                 *         )
             *)
         *)
     *)
     *
     */
    public function logout()
    {
        return $this->authService->logout();
    }
}
