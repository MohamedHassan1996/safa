<?php

namespace App\Http\Controllers\Api\Private\Charity;

use App\Http\Controllers\Controller;
use App\Http\Requests\CharityCase\CreateCharityCaseRequest;
use App\Http\Requests\CharityCase\UpdateCharityCaseRequest;
use App\Http\Resources\CharityCase\AllCharityCaseCollection;
use App\Http\Resources\CharityCase\CharityCaseResource;
use App\Models\CharityCase\CharityCaseDocument;
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

    public function create(CreateCharityCaseRequest $createCharityCaseRequest)
    {
        try {
            DB::beginTransaction();


            $charityCase = $this->charityCaseService->createCharityCase($createCharityCaseRequest->validated());

            $files = $createCharityCaseRequest->validated()['files']??[];



            foreach ($files as $file) {
                $path = $this->uploadService->uploadFile($file, 'charity_cases/' . $charityCase->id);

                CharityCaseDocument::create([
                    'charity_case_id' => $charityCase->id,
                    'path' => $path,
                    'type' => 0
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

    public function edit(Request $request)
    {
        $charityCase  =  $this->charityCaseService->editCharityCase($request->charityCaseId);

        return new CharityCaseResource($charityCase);

    }

    /**
     * Update the specified resource in storage.
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
