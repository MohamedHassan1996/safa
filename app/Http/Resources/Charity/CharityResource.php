<?php

namespace App\Http\Resources\Charity;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class CharityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'charityId' => $this->id,
            'name' => $this->name,
            'note' => $this->note??'',
            'isActive'=>$this->is_active
        ];
    }
}
