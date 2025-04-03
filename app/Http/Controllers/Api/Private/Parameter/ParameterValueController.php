<?php

namespace App\Http\Controllers\Api\Private\Parameter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Paramter\CreateParameterValueRequest;
use App\Http\Requests\Paramter\UpdateParameterValueRequest;
use App\Http\Resources\Parameter\AllParameterValueCollection;
use App\Http\Resources\Parameter\ParameterValueResource;
use App\Services\Parameter\ParameterService;
use App\Utils\PaginateCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class ParameterValueController extends Controller implements HasMiddleware
{
    private $parameterService;

    public function __construct(ParameterService $parameterService)
    {
        $this->parameterService = $parameterService;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware('permission:all_parameters', only:['index']),
            new Middleware('permission:create_parameter', only:['create']),
            new Middleware('permission:edit_parameter', only:['edit']),
            new Middleware('permission:update_parameter', only:['update']),
            new Middleware('permission:destroy_parameter', only:['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $parameters = $this->parameterService->allParameters($request->parameterOrder);

        return response()->json(
            new AllParameterValueCollection(PaginateCollection::paginate($parameters, $request->pageSize?$request->pageSize:10))
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateParameterValueRequest $request)
    {
        try {
            DB::beginTransaction();
            $parameter = $this->parameterService->createParameter($request->validated());
            DB::commit();
            return response()->json([
                'message' => __('messages.success.created')
            ]);
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
        $parameter = $this->parameterService->editParameter($request->parameterValueId);

        return response()->json(new ParameterValueResource($parameter));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParameterValueRequest $request)
    {

        try {
            DB::beginTransaction();
            $parameter = $this->parameterService->updateParameter($request->validated());
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
    public function destroy(Request $request)
    {
        $this->parameterService->deleteParameter($request->parameterValueId);

        return response()->json([
            'message' => __('messages.success.deleted')
        ]);

    }

}
