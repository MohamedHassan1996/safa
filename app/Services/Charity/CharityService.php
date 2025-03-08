<?php

namespace App\Services\Charity;

use App\Enums\Charity\CharityStatus;
use App\Filters\Charity\FilterCharity;
use App\Models\Charity\Charity;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CharityService{



    public function allCharities()
    {
        $charities = QueryBuilder::for(Charity::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterCharity()), // Add a custom search filter
                AllowedFilter::exact('isActive', 'is_active')
            ])
            ->get();

        return $charities;

    }

    public function createCharity(array $charityData): Charity
    {

        $charity = Charity::create([
            'name' => $charityData['name'],
            'is_active' => CharityStatus::from($charityData['isActive'])->value,
            'note' => $charityData['note']??'',
        ]);


        return $charity;

    }

    public function editCharity(int $charityId)
    {
        return Charity::find($charityId);
    }

    public function updateCharity(array $charityData): Charity
    {

        $charity = Charity::create([
            'name' => $charityData['name'],
            'is_active' => CharityStatus::from($charityData['isActive'])->value,
            'note' => $charityData['note']??'',
        ]);


        return $charity;

    }


    public function deleteCharity(int $charityId)
    {

        $charity = Charity::find($charityId);

        $charity->delete();

    }

}
