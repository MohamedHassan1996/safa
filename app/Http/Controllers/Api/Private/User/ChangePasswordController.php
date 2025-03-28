<?php

namespace App\Http\Controllers\Api\Private\User;

use App\Enums\ResponseCode\HttpStatusCode;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
        ];
    }

    /**
     * Handle password change request.
     */
    public function __invoke(ChangePasswordRequest $request)
    {
        $authUser = $request->user();

        if (!Hash::check($request->currentPassword, $authUser->password)) {
            return response()->json([
                'message' => 'الرقم السري غير صحيح',
            ], 401);
        }

        // Update password securely
        $authUser->update([
            'password' => Hash::make(value: $request->password),
        ]);

        $authUser->tokens()->delete();

        return response()->json([
            'message' => 'تم تغيير الرقم السري بنجاح',
        ]);
    }
}
