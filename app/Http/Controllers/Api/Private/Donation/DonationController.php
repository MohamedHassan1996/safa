<?php

namespace App\Http\Controllers\Api\Private\Donation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Donation\CreateDonationRequest;
use App\Http\Requests\Donation\UpdateDonationRequest;
use App\Http\Resources\Donation\AllDonationCollection;
use App\Http\Resources\Donation\DonationResource;
use App\Utils\PaginateCollection;
use App\Services\Donation\DonationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class DonationController extends Controller implements HasMiddleware
{
    protected $donationService;

    public function __construct(DonationService $donationService)
    {
        $this->donationService = $donationService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware('permission:all_donations', only:['index']),
            new Middleware('permission:create_donation', only:['create']),
            new Middleware('permission:edit_donation', only:['edit']),
            new Middleware('permission:update_donation', only:['update']),
            new Middleware('permission:destroy_donation', only:['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allDonations = $this->donationService->allDonations();

        return response()->json(
            new AllDonationCollection(PaginateCollection::paginate($allDonations, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */


    
    /**
     * @OA\Post(
     *     path="/donations/create",
     *     summary="Auto generated",
     *     tags={"DonationController" },
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    
    public function create(CreateDonationRequest $createDonationRequest)
    {
        try {
            DB::beginTransaction();

            $this->donationService->createDonation($createDonationRequest->validated());

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
     *     path="/donations/edit",
     *     summary="Auto generated",
     *     tags={"DonationController" },
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    
    public function edit(Request $request)
    {
        $donation  =  $this->donationService->editDonation($request->donationId);

        return new DonationResource($donation);

    }

    /**
     * Update the specified resource in storage.
     */
    
    /**
     * @OA\Put(
     *     path="/donations/update",
     *     summary="Auto generated",
     *     tags={"DonationController" },
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    
    public function update(UpdateDonationRequest $updateDonationRequest)
    {

        try {
            DB::beginTransaction();
            $this->donationService->updateDonation($updateDonationRequest->validated());
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
     *     path="/donations/destroy",
     *     summary="Auto generated",
     *     tags={"DonationController" },
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
            $this->donationService->deleteDonation($request->donationId);
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
