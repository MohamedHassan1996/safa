<?php

namespace App\Http\Controllers\Api\Private\Charity;

use App\Http\Controllers\Controller;
use App\Http\Requests\CharityCase\CreateCharityCaseRequest;
use App\Http\Requests\CharityCase\UpdateCharityCaseRequest;
use App\Http\Resources\CharityCase\AllCharityCaseCollection;
use App\Http\Resources\CharityCase\CharityCaseResource;
use App\Models\CharityCase\CharityCaseDocument;
use App\Models\CharityCaseChildren;
use App\Utils\PaginateCollection;
use App\Services\CharityCase\CharityCaseService;
use App\Services\Upload\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;




class CharityCaseController extends Controller implements HasMiddleware
{
    protected $charityCaseService;
    protected $uploadService;


    public function __construct(CharityCaseService $charityCaseService, UploadService $uploadService)
    {
        $this->charityCaseService = $charityCaseService;
        $this->uploadService = $uploadService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware('permission:all_charity_cases', only:['index']),
            new Middleware('permission:create_charity_case', only:['create']),
            new Middleware('permission:edit_charity_case', only:['edit']),
            new Middleware('permission:update_charity_case', only:['update']),
            new Middleware('permission:destroy_charity_case', only:['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allCharityCases = $this->charityCaseService->allCharityCases();

        return response()->json(
            new AllCharityCaseCollection(PaginateCollection::paginate($allCharityCases, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * @OA\Post(
     *     path="/charity-cases/create",
     *     summary="Auto generated",
     *     tags={"CharityCaseController" },
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */

    public function create(CreateCharityCaseRequest $createCharityCaseRequest)
    {
        try {
            DB::beginTransaction();


            $charityCase = $this->charityCaseService->createCharityCase($createCharityCaseRequest->validated());

            $files = $createCharityCaseRequest->validated()['files']??[];
            $children = $createCharityCaseRequest->validated()['children']??[];


            foreach ($files as $file) {
                $path = $this->uploadService->uploadFile($file['path'], 'charity_cases/' . $charityCase->id);

                CharityCaseDocument::create([
                    'charity_case_id' => $charityCase->id,
                    'path' => $path,
                    'type' => 0
                ]);
            }

            foreach ($children as $child) {
                CharityCaseChildren::create([
                    'charity_case_id' => $charityCase->id,
                    'name' => $child['name'],
                    'age' => $child['age']??0,
                    'note' => $child['note']??'',
                    'education_level_id' => $child['educationLevelId']??null,
                    'donation_type_id' => $child['donationTypeId']??null,
                ]);
            }

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
     *     path="/charity-cases/edit",
     *     summary="Auto generated",
     *     tags={"CharityCaseController" },
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */

    public function edit(Request $request)
    {
        $charityCase  =  $this->charityCaseService->editCharityCase($request->charityCaseId);

        return new CharityCaseResource($charityCase);

    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *     path="/charity-cases/update",
     *     summary="Auto generated",
     *     tags={"CharityCaseController" },
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */

    public function update(UpdateCharityCaseRequest $updateCharityCaseRequest)
    {

        try {
            DB::beginTransaction();
            $this->charityCaseService->updateCharityCase($updateCharityCaseRequest->validated());
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
     *     path="/charity-cases/destroy",
     *     summary="Auto generated",
     *     tags={"CharityCaseController" },
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
            $this->charityCaseService->deleteCharityCase($request->charityCaseId);
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
