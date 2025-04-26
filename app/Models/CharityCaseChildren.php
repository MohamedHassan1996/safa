<?php

namespace App\Models;

use App\Models\CharityCase\CharityCase;
use App\Models\Parameter\ParameterValue;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;

class CharityCaseChildren extends Model
{
    use CreatedUpdatedBy;

    protected $table = 'charity_case_children';

    protected $fillable = [
        'name',
        'age',
        'note',
        'charity_case_id',
        'education_level_id',
        'donation_type_id',
    ];

    public function charityCase()
    {
        return $this->belongsTo(CharityCase::class);
    }

    public function educationLevel()
    {
        return $this->belongsTo(ParameterValue::class, 'education_level_id');
    }

    public function donationType()
    {
        return $this->belongsTo(ParameterValue::class, 'donation_type_id');
    }
}
