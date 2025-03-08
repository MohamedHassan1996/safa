<?php

namespace App\Models\Charity;

use App\Enums\Chairty\ChairtyStatus;
use Illuminate\Database\Eloquent\Model;

class Charity extends Model
{
    protected $fillable = [
        'name',
        'note',
        'is_active'
    ];


    protected function casts(): array
    {
        return [
            'is_active' => ChairtyStatus::class
        ];
    }
}
