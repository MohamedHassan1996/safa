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
        $charities = QueryBuilder::for(Donation::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterDonation()), // Add a custom search filter
                AllowedFilter::exact('type')
            ])
            ->get();

        return $charities;

    }

    public function createDonation(array $donationData): Donation
    {

        $donation = Donation::create([
            'amount' => $donationData['amount'],
            'date' => $donationData['date'],
            'type' => DonationType::from($donationData['type'])->value,
            'note' => $donationData['note']??'',
            'charity_case_id' => $donationData['charityCaseId'],
        ]);

        return $donation;

    }

    public function editDonation(int $donationId)
    {
        return Donation::find($donationId);
    }

    public function updateDonation(array $donationData)
    {

        $donation = Donation::find($donationData['donationId']);

        $donation->fill([
            'amount' => $donationData['amount'],
            'date' => $donationData['date'],
            'type' => DonationType::from($donationData['type'])->value,
            'note' => $donationData['note']??'',
            'charity_case_id' => $donationData['charityCaseId'],
        ]);

        $donation->save();


        return $donation;

    }


    public function deleteDonation(int $donationId)
    {

        $donation = Donation::find($donationId);

        $donation->delete();

    }

}
