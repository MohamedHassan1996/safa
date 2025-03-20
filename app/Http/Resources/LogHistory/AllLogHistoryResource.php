<?php

namespace App\Http\Resources\LogHistory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class AllLogHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return Parent::toArray($request);
    }
}
