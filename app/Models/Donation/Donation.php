<?php

namespace App\Models\Donation;

use App\Enums\Donation\DonationType;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'amount',
        'date',
        'type',
        'note',
        'charity_case_id',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->number = time() . mt_rand(1000, 9999);
        });
    }

    protected function casts(): array
    {
        return [
            'type' => DonationType::class,
        ];
    }

}
