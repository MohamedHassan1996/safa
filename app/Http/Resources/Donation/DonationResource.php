<?php

namespace App\Http\Resources\Donation;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class DonationResource extends JsonResource
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
            'charityCaseId' => $this->charity_case_id,
            'date' => Carbon::parse($this->date)->format('d/m/Y')
        ];
    }
}
