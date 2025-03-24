<?php

namespace App\Http\Resources\Donation;

use Carbon\Carbon;
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
            'amount' => $this->amount?$this->amount.'ج':0,
            'type' => $this->type,
            'date' => Carbon::parse($this->date)->format('d/m/Y'),
            'charityCaseName' => $this->charityCase?->name??'',
            'charityName' => $this->charity?->name??''
        ];
    }
}
