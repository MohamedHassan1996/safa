<?php

namespace App\Models\CharityCase;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CharityCaseDocument extends Model
{
    protected $fillable = [
        'type',
        'path',
        'charity_case_id',
    ];

    protected function path(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::disk('public')->url($value) : "",
        );
    }
}
