<?php

namespace App\Models\Donation;

use App\Enums\Donation\DonationType;
use App\Models\Charity\Charity;
use App\Models\CharityCase\CharityCase;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use CreatedUpdatedBy;
    protected $fillable = [
        'amount',
        'date',
        'type',
        'note',
        'charity_case_id',
        'charity_id'
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

    public function charity()
    {
        return $this->belongsTo(Charity::class);
    }

    public function CharityCase()
    {
        return $this->belongsTo(CharityCase::class);
    }

}
