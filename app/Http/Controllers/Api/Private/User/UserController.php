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
use OpenApi\Annotations as OA;


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
            new Middleware('permission:update_user', only:['update']),
            new Middleware('permission:destroy_user', only:['destroy']),
        ];
    }


    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Get list of users",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number for pagination",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="pageSize",
     *         in="query",
     *         required=false,
     *         description="Number of users per page",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="users", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="userId", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="John Doe"),
     *                         @OA\Property(property="username", type="string", example="johndoe"),
     *                         @OA\Property(property="status", type="string", example="active"),
     *                         @OA\Property(property="avatar", type="string", example="https://example.com/avatar.jpg"),
     *                         @OA\Property(property="roleName", type="string", example="Admin"),
     *                         @OA\Property(property="charityName", type="string", example="Charity Org")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="pagination", type="object",
     *                 @OA\Property(property="total", type="integer", example=100),
     *                 @OA\Property(property="count", type="integer", example=10),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="total_pages", type="integer", example=10)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
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


    /**
     * @OA\Post(
     *     path="/users/create",
     *     summary="Auto generated",
     *     tags={"UserController" },
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
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


    /**
     * @OA\Get(
     *     path="/users/edit",
     *     summary="Auto generated",
     *     tags={"UserController" },
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */

    public function edit(Request $request)
    {
        $user  =  $this->userService->editUser($request->userId);

        return new UserResource($user);

    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *     path="/users/update",
     *     summary="Auto generated",
     *     tags={"UserController" },
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
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

    /**
     * @OA\Delete(
     *     path="/users/destroy",
     *     summary="Auto generated",
     *     tags={"UserController" },
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
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
