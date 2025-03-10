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
            'date_of_birth'=>$this->date_of_birth??'',
            'social_status'=>$this->social_status,
            'note'=>$this->note??'',
        ];
    }
}
