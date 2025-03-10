<?php

namespace App\Http\Resources\CharityCaseDocument;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class CharityCaseDocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'charityCaseDocumentId' => $this->id,
            'path' => $this->path,
            'type' => $this->type,
        ];
    }
}
