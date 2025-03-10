<?php

namespace App\Models\CharityCase;

use App\Enums\Charity\CharityCaseGender;
use App\Enums\Charity\CharityCaseSocialStatus;
use Illuminate\Database\Eloquent\Model;

class CharityCase extends Model
{

    protected $table = 'charity_cases';

    protected $fillable = [
        'national_id',
        'name',
        'phone',
        'address',
        'gender',
        'date_of_birth',
        'social_status',
        'note'
    ];

    protected function casts(): array
    {
        return [
            'gender' => CharityCaseGender::class,
            'social_status' => CharityCaseSocialStatus::class
        ];
    }

    public function documents()
    {
        return $this->hasMany(CharityCaseDocument::class);
    }
}
