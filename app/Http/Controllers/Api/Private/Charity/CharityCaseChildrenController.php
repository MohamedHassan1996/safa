<?php

namespace App\Http\Controllers\Api\Private\Charity;

use App\Http\Controllers\Controller;
use App\Http\Resources\CharityCaseChildren\AllCharityCaseChildrenCollection;
use App\Http\Resources\CharityCaseChildren\CharityCaseChildrenResource;
use App\Http\Resources\CharityCaseDocument\AllCharityCaseDocumentCollection;
use App\Models\CharityCase\CharityCase;
use App\Models\CharityCase\CharityCaseDocument;
use App\Models\CharityCaseChildren;
use App\Utils\PaginateCollection;
use App\Services\Upload\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class CharityCaseChildrenController extends Controller implements HasMiddleware
{    protected $uploadService;


    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware('permission:all_charity_case_children', only:['index']),
            new Middleware('permission:create_charity_case_children', only:['create']),
            new Middleware('permission:edit_charity_case_children', only:['edit']),
            new Middleware('permission:update_charity_case_children', only:['update']),
            new Middleware('permission:destroy_charity_case_children', only:['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allCharityCaseChildren = CharityCaseChildren::where('charity_case_id', $request->charityCaseId)->get();

        return response()->json(
            new AllCharityCaseChildrenCollection(PaginateCollection::paginate($allCharityCaseChildren, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * @OA\Post(
     *     path="/charity-case-documents/create",
     *     summary="Auto generated",
     *     tags={"CharityCaseDocumentController" },
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();

            $charityCaseChild = CharityCaseChildren::create([
                'charity_case_id' => $request->charityCaseId,
                'name' => $request->name,
                'age' => $request->age??0,
                'note' => $request->note??'',
                'education_level_id' => $request->educationLevelId??null,
                'donation_type_id' => $request->donationTypeId??null,
            ]);

            DB::commit();

            return response()->json([
                'message' => __('messages.success.created')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    public function edit(Request $request){

        $charityCaseChild  =  CharityCaseChildren::find($request->charityCaseChildId);

        return new CharityCaseChildrenResource($charityCaseChild);
    }

    public function update(Request $request){

        try {
            DB::beginTransaction();
            $charityCaseChild = CharityCaseChildren::find($request->charityCaseChildId);
            $charityCaseChild->update([
                'name' => $request->name,
                'age' => $request->age??0,
                'note' => $request->note??'',
                'education_level_id' => $request->educationLevelId??null,
                'donation_type_id' => $request->donationTypeId??null,
            ]);
            DB::commit();
            return response()->json([
                'message' => __('messages.success.updated')
            ]);
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
     *     path="/charity-case-documents/destroy",
     *     summary="Auto generated",
     *     tags={"CharityCaseDocumentController" },
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
            $charityCaseChild = CharityCaseChildren::find($request->charityCaseChildId);

            if ($charityCaseChild) {
                $charityCaseChild->delete();
            }

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
