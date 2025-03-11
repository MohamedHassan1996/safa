<?php

namespace App\Http\Resources\Donation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class AllDonationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'donationId' => $this->id,
            'donationNumber' => $this->number,
            'note' => $this->note??'',
            'amount' => $this->amount??0,
            'type' => $this->type,
            'date' => $this->date

        ];
    }
}
