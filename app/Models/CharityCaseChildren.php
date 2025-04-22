<?php

namespace App\Models;

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
}
