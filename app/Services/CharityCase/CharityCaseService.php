<?php

namespace App\Services\CharityCase;

use App\Enums\Charity\CharityCaseGender;
use App\Enums\Charity\CharityCaseSocialStatus;
use App\Enums\CharityCase\CharityCaseStatus;
use App\Filters\CharityCase\FilterCharityCase;
use App\Filters\CharityCase\FilterCharityCaseRole;
use App\Models\CharityCase\CharityCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CharityCaseService{
    protected $uploadService;

    public function allCharityCases()
    {
        $charityCases = QueryBuilder::for(CharityCase::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterCharityCase()), // Add a custom search filter
                AllowedFilter::exact('socialStatus', 'social_status'),
                AllowedFilter::exact('gender'),
            ])
            ->get();

        return $charityCases;

    }

    public function createCharityCase(array $charityCaseData): CharityCase
    {

        $charityCase = CharityCase::create([
            'national_id' => $charityCaseData['nationalId'],
            'name' => $charityCaseData['name'],
            'email' => $charityCaseData['email']??'',
            'phone' => $charityCaseData['phone']??'',
            'address' => $charityCaseData['address']??'',
            'social_status' => CharityCaseSocialStatus::from($charityCaseData['socialStatus'])->value,
            'gender' => CharityCaseGender::from($charityCaseData['gender'])->value,
            'date_of_birth' => $charityCaseData['dateOfBirth']??null,
            'note' => $charityCaseData['note']??null

        ]);

        return $charityCase;

    }

    public function editCharityCase(int $charityCaseId)
    {
        return CharityCase::findOrFail($charityCaseId);
    }

    public function updateCharityCase(array $charityCaseData)
    {

        $charityCase = CharityCase::find($charityCaseData['charityCaseId']);

        $charityCase->fill([
            'national_id' => $charityCaseData['nationalId'],
            'name' => $charityCaseData['name'],
            'email' => $charityCaseData['email']??'',
            'phone' => $charityCaseData['phone']??'',
            'address' => $charityCaseData['address']??'',
            'social_status' => CharityCaseSocialStatus::from($charityCaseData['socialStatus'])->value,
            'gender' => CharityCaseGender::from($charityCaseData['gender'])->value,
            'date_of_birth' => $charityCaseData['dateOfBirth']??null
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
