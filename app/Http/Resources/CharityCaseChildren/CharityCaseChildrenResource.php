<?php

namespace App\Http\Resources\CharityCaseChildren;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class CharityCaseChildrenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'charityCaseChildId' => $this->id,
            'name' => $this->name,
            'age' => $this->age??'',
            'note' => $this->note??'',
            'educationLevelId' => $this->education_level_id??'',
            'donationTypeId' => $this->donation_type_id??''
        ];
    }
}
