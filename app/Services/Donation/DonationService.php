<?php

namespace App\Services\Donation;

use App\Enums\Donation\DonationType;
use App\Filters\Donation\FilterDonation;
use App\Models\Donation\Donation;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DonationService{



    public function allDonations()
    {
        $filters = request()->input('filter', []);
        $startDate = $filters['startDate'] ?? null;
        $endDate = $filters['endDate'] ?? null;

        $donations = QueryBuilder::for(Donation::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterDonation()), // Add a custom search filter
                AllowedFilter::exact('type'),
                AllowedFilter::exact('charityCaseId', 'charity_case_id'),
                AllowedFilter::exact('charityId', 'charity_id')
            ])
            ->when(
                !empty($startDate) && !empty($endDate),
                function ($query) use ($startDate, $endDate) {
                    $query->whereDate('date', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
                }
            )
            ->when(
                !empty($endDate) && empty($startDate),
                function ($query) use ($endDate) {

                    $query->whereDate('date', '<=', $endDate);
                }
            )
            ->when(
                empty($endDate) && !empty($startDate),
                function ($query) use ($startDate) {

                    $query->whereDate('date', '=', $startDate);
                }
            )
            ->with([
                'charityCase',
                'charity',
            ])
            ->get();

        return $donations;

    }

    public function createDonation(array $donationData): Donation
    {
        $auth = auth()->user();
        $currentUserRole = $auth->getRoleNames()[0];
        $donation = new Donation();
        $donation->amount = $donationData['amount']??0;
        $donation->type = DonationType::from($donationData['type'])->value;
        $donation->note = $donationData['note']??0;
        $donation->charity_case_id = $donationData['charityCaseId'];
        $donation->date = $donationData['date'];

        if($currentUserRole == 'مدير عام'){
            $donation->charity_id = $donationData['charityId'];
        }else{
            $donation->charity_id = auth()->user()->charity_id;
        }

        $donation->save();


        return $donation;

    }

    public function editDonation(int $donationId)
    {
        return Donation::find($donationId);
    }

    public function updateDonation(array $donationData)
    {

        $auth = auth()->user();
        $currentUserRole = $auth->getRoleNames()[0];

        $donation = Donation::find($donationData['donationId']);
        $donation->amount = $donationData['amount']??0;
        $donation->type = DonationType::from($donationData['type'])->value;
        $donation->note = $donationData['note']??0;
        $donation->charity_case_id = $donationData['charityCaseId'];

        if($currentUserRole == 'مدير عام'){
            $donation->charity_id = $donationData['charityId'];
        }else{
            $donation->charity_id = auth()->user()->charity_id;
        }

        $donation->save();


        return $donation;

    }


    public function deleteDonation(int $donationId)
    {

        $donation = Donation::find($donationId);

        $donation->delete();

    }

}
