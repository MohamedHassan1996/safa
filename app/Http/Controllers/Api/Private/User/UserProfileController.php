<?php

namespace App\Http\Controllers\Api\Private\User;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserProfileRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Services\Upload\UploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller implements HasMiddleware
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
        ];
    }


    /**
     * Show the form for editing the specified resource.
     */

    public function show(Request $request)
    {

        return  new UserResource($request->user());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserProfileRequest $request)
    {

        $authUser = $request->user();

        $userData = $request->validated();

        $avatarPath = null;

        if(isset($userData['avatar']) && $userData['avatar'] instanceof UploadedFile){
            $avatarPath =  $this->uploadService->uploadFile($userData['avatar'], $userData['avatar']??'avatars');
        }

        $authUser->name = $userData['name']??'';
        $authUser->email = $userData['email']??'';
        $authUser->phone = $userData['phone']??'';
        $authUser->address = $userData['address'??''];

        if($avatarPath){
            Storage::disk('public')->delete($authUser->getRawOriginal('avatar'));
            $authUser->avatar = $avatarPath;
        }

        $authUser->save();

        return response()->json([
            'message' => 'تم تحديث الملف الشخصي بنجاح',
        ]);

    }


}
