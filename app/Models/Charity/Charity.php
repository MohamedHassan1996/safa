<?php

namespace App\Models\Charity;

use App\Enums\Charity\CharityStatus;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;

class Charity extends Model
{
    //use CreatedUpdatedBy;
    protected $fillable = [
        'name',
        'note',
        'is_active'
    ];


    protected function casts(): array
    {
        return [
            'is_active' => CharityStatus::class
        ];
    }
}
