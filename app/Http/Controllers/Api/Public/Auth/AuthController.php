<?php

namespace App\Http\Controllers\Api\Public\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

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
    public function login(LoginRequest $loginReq)
    {
        return $this->authService->login($loginReq->validated());
    }

    /*
    ** logout method
    */
    public function logout()
    {
        return $this->authService->logout();
    }
}
