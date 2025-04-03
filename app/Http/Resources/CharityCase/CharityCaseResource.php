<?php

namespace App\Http\Resources\CharityCase;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class CharityCaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'charityCaseId' => $this->id,
            'nationalId' => $this->national_id,
            'name' => $this->name,
            'phone' => $this->phone??'',
            'address'=>$this->address??'',
            'gender'=>$this->gender,
            'dateOfBirth'=>$this->date_of_birth??'',
            'socialStatusId'=>$this->social_status_id,
            'note'=>$this->note??'',
            'areaId'=>$this->area_id??'',
            'donationPriorityId'=>$this->donation_priority_id??'',
            'numberOfChildren'=>$this->number_of_children??0,
            'housingType'=>$this->housing_type
        ];
    }
}
