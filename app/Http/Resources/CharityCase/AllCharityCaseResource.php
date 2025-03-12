<?php

namespace App\Http\Resources\CharityCase;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class AllCharityCaseResource extends JsonResource
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
            'socialStatus'=>$this->social_status,
        ];
    }
}
