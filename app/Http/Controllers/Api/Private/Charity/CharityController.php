<?php

namespace App\Http\Controllers\Api\Private\Charity;

use App\Http\Controllers\Controller;
use App\Http\Requests\Charity\CreateCharityRequest;
use App\Http\Requests\Charity\UpdateCharityRequest;
use App\Http\Resources\Charity\AllCharityCollection;
use App\Http\Resources\Charity\CharityResource;
use App\Utils\PaginateCollection;
use App\Services\Charity\CharityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class CharityController extends Controller implements HasMiddleware
{
    protected $charityService;

    public function __construct(CharityService $charityService)
    {
        $this->charityService = $charityService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware('permission:all_charities', only:['index']),
            new Middleware('permission:create_charity', only:['create']),
            new Middleware('permission:edit_charity', only:['edit']),
            new Middleware('permission:update_charity', only:['update']),
            new Middleware('permission:destroy_charity', only:['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allCharities = $this->charityService->allCharities();

        return response()->json(
            new AllCharityCollection(PaginateCollection::paginate($allCharities, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */



    /**
     * @OA\Post(
     *     path="/charities/create",
     *     summary="Auto generated",
     *     tags={"CharityController" },
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */

    public function create(CreateCharityRequest $createCharityRequest)
    {
        try {
            DB::beginTransaction();

            $this->charityService->createCharity($createCharityRequest->validated());

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
     *     path="/charities/edit",
     *     summary="Auto generated",
     *     tags={"CharityController" },
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */

    public function edit(Request $request)
    {
        $charity  =  $this->charityService->editCharity($request->charityId);

        return new CharityResource($charity);

    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *     path="/charities/update",
     *     summary="Auto generated",
     *     tags={"CharityController" },
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */

    public function update(UpdateCharityRequest $updateCharityRequest)
    {

        try {
            DB::beginTransaction();
            $this->charityService->updateCharity($updateCharityRequest->validated());
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
     *     path="/charities/destroy",
     *     summary="Auto generated",
     *     tags={"CharityController" },
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
            $this->charityService->deleteCharity($request->charityId);
            DB::commit();
            return response()->json([
                'message' => __('messages.success.deleted')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

}
