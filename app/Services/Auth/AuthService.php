<?php

namespace App\Services\Auth;

use App\Enums\User\UserStatus;
use App\Http\Resources\User\LoggedInUserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\UserRolePremission\UserPermissionService;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected $userPermissionService;

    public function __construct(UserPermissionService $userPermissionService)
    {
        $this->userPermissionService = $userPermissionService;
    }

    public function login(array $data)
    {
        try {
            $user = User::where('username', $data['username'])->first();

            if (!$user || !Hash::check($data['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'message' => __('auth.failed'),
                ]);
            }

            if ($user->status == UserStatus::INACTIVE) {
                return response()->json([
                    'message' => 'هذا الحساب غير مفعل!',
                ], 401);
            }

            // // Revoke old tokens (optional)
            $user->tokens()->delete();

            // Generate a new token (DO NOT return it directly)
            $token = $user->createToken('auth_token')->plainTextToken;

            // Store the token in an HTTP-only cookie
            //$cookie = cookie('auth_token', $token, 600 * 10, '/', null, true, true); // 1 day, secure, HTTP-only

            return response()->json([
                'profile' => new LoggedInUserResource($user),
                'role' => $user->roles->first()->name,
                'permissions' => $this->userPermissionService->getUserPermissions($user),
                'tokenDetails' => [
                    'token' => $token,
                    'expiresIn' => 60 * 10
                ],
            ]);//->withCookie($cookie, true);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function logout()
    {
        $user = auth()->user();


        if ($user) {
            $user->tokens()->delete(); // Revoke all tokens
        }

        return response()->json(['message' => 'You have logged out'])
            ->withCookie(cookie()->forget('auth_token'));
    }
}
