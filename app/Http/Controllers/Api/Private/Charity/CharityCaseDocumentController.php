<?php

namespace App\Http\Controllers\Api\Private\Charity;

use App\Http\Controllers\Controller;
use App\Http\Requests\CharityCase\CreateCharityCaseRequest;
use App\Http\Requests\CharityCase\UpdateCharityCaseRequest;
use App\Http\Resources\CharityCase\AllCharityCaseCollection;
use App\Http\Resources\CharityCase\CharityCaseResource;
use App\Http\Resources\CharityCaseDocument\AllCharityCaseDocumentCollection;
use App\Models\CharityCase\CharityCase;
use App\Models\CharityCase\CharityCaseDocument;
use App\Utils\PaginateCollection;
use App\Services\CharityCase\CharityCaseService;
use App\Services\Upload\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class CharityCaseDocumentController extends Controller implements HasMiddleware
{    protected $uploadService;


    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware('permission:all_charity_case_documents', only:['index']),
            new Middleware('permission:create_charity_case_document', only:['create']),
            new Middleware('permission:edit_charity_case_document', only:['edit']),
            new Middleware('permission:update_charity_case_document', only:['update']),
            new Middleware('permission:destroy_charity_case_document', only:['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allCharityCaseDocuments = CharityCaseDocument::where('charity_case_id', $request->charityCaseId)->get();

        return response()->json(
            new AllCharityCaseDocumentCollection(PaginateCollection::paginate($allCharityCaseDocuments, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();


            $charityCase = CharityCase::find($request->charityCaseId);

            $files = $request->file('files');

            foreach ($files as $file) {
                $path = $this->uploadService->uploadFile($file['path'], 'charity_cases/' . $charityCase->id);

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
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        try {
            DB::beginTransaction();
            $charityCaseDocument = CharityCaseDocument::find($request->charityCaseDocumentId);

            if ($charityCaseDocument) {
                Storage::disk('public')->delete($charityCaseDocument->path);
                $charityCaseDocument->delete();
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
