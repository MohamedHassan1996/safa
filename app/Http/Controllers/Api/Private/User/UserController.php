<?php

namespace App\Http\Controllers\Api\Private\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\AllUserDataResource;
use App\Http\Resources\User\AllUserCollection;
use App\Http\Resources\User\UserResource;
use App\Utils\PaginateCollection;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class UserController extends Controller implements HasMiddleware
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware('permission:all_users', only:['index']),
            new Middleware('permission:create_user', only:['create']),
            new Middleware('permission:edit_user', only:['edit']),
            new Middleware('permission:destroy_user', only:['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allUsers = $this->userService->allUsers();

        return response()->json(
            new AllUserCollection(PaginateCollection::paginate($allUsers, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(CreateUserRequest $createUserRequest)
    {
        try {
            DB::beginTransaction();

            $this->userService->createUser($createUserRequest->validated());

            DB::commit();

            return response()->json([
                'message' => __('messages.success.created')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Request $request)
    {
        $user  =  $this->userService->editUser($request->userId);

        return new UserResource($user);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $updateUserRequest)
    {

        try {
            DB::beginTransaction();
            $this->userService->updateUser($updateUserRequest->validated());
            DB::commit();
            return response()->json([
                 'message' => __('messages.success.updated')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        try {
            DB::beginTransaction();
            $this->userService->deleteUser($request->userId);
            DB::commit();
            return response()->json([
                'message' => __('messages.success.deleted')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    public function changeStatus(Request $request)
    {

        try {
            DB::beginTransaction();
            $this->userService->changeUserStatus($request->userId, $request->status);
            DB::commit();

            return response()->json([
                'message' => __('messages.success.updated')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

}
