<?php

namespace App\Services\CharityCase;

use App\Enums\Charity\CharityCaseGender;
use App\Enums\Charity\HousingType;
use App\Filters\CharityCase\FilterCharityCase;
use App\Filters\CharityCase\FilterCharityCaseAge;
use App\Models\CharityCase\CharityCase;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CharityCaseService{
    protected $uploadService;

    public function allCharityCases()
    {
        $charityCases = QueryBuilder::for(CharityCase::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterCharityCase()), // Add a custom search filter
                AllowedFilter::exact('socialStatus', 'social_status_id'),
                AllowedFilter::exact('area', 'area_id'),
                AllowedFilter::exact('donationPriority', 'donation_priority_id'),
                AllowedFilter::exact('housingType', 'housing_type'),
                AllowedFilter::exact('gender'),
                AllowedFilter::custom('age', new FilterCharityCaseAge()),
            ])
            ->with('area', 'donationPriority', 'socialStatus')
            ->get();

        return $charityCases;

    }

    public function createCharityCase(array $charityCaseData): CharityCase
    {
        $auth = auth()->user();
        $charityCase = CharityCase::create([
            'national_id' => $charityCaseData['nationalId'],
            'pair_national_id' => $charityCaseData['pairNationalId']??'',
            'pair_name' => $charityCaseData['pairName']??'',
            'charity_id' => $charityCaseData['charityId']??$auth->chairty_id,
            'name' => $charityCaseData['name'],
            'email' => $charityCaseData['email']??'',
            'phone' => $charityCaseData['phone']??'',
            'address' => $charityCaseData['address']??'',
            'social_status_id' => $charityCaseData['socialStatusId']??null,
            'gender' => CharityCaseGender::from($charityCaseData['gender'])->value,
            'date_of_birth' => $charityCaseData['dateOfBirth']??null,
            'note' => $charityCaseData['note']??null,
            'area_id' => $charityCaseData['areaId']??null,
            'donation_priority_id' => $charityCaseData['donationPriorityId']??null,
            'number_of_children' => $charityCaseData['numberOfChildren']??0,
            'housing_type' => HousingType::from($charityCaseData['housingType'])->value
        ]);

        return $charityCase;

    }

    public function editCharityCase(int $charityCaseId)
    {
        return CharityCase::findOrFail($charityCaseId);
    }

    public function updateCharityCase(array $charityCaseData)
    {
        $auth = auth()->user();

        $charityCase = CharityCase::find($charityCaseData['charityCaseId']);

        $charityCase->fill([
            'national_id' => $charityCaseData['nationalId'],
            'pair_national_id' => $charityCaseData['pairNationalId']??'',
            'pair_name' => $charityCaseData['pairName']??'',
            'charity_id' => $charityCaseData['charityId']??$auth->chairty_id,
            'name' => $charityCaseData['name'],
            'email' => $charityCaseData['email']??'',
            'phone' => $charityCaseData['phone']??'',
            'address' => $charityCaseData['address']??'',
            'social_status_id' => $charityCaseData['socialStatusId']??null,
            'gender' => CharityCaseGender::from($charityCaseData['gender'])->value,
            'date_of_birth' => $charityCaseData['dateOfBirth']??null,
            'note' => $charityCaseData['note']??null,
            'area_id' => $charityCaseData['areaId']??null,
            'donation_priority_id' => $charityCaseData['donationPriorityId']??null,
            'number_of_children' => $charityCaseData['numberOfChildren']??0,
            'housing_type' => HousingType::from($charityCaseData['housingType'])->value
        ]);

        $charityCase->save();

        return $charityCase;
    }


    public function deleteCharityCase(int $charityCaseId)
    {

        $charityCase = CharityCase::find($charityCaseId);

        $charityCase->delete();

    }

}
