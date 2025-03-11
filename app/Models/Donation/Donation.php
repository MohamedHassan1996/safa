<?php

namespace App\Models\Donation;

use App\Enums\Donation\DonationType;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'number',
        'amount',
        'date',
        'type',
        'note',
        'charity_case_id',
    ];

    protected function casts(): array
    {
        return [
            'type' => DonationType::class,
        ];
    }

}
