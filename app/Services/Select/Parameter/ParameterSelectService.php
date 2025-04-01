<?php

namespace App\Services\Select\Parameter;

use App\Models\Parameter\ParameterValue;

class ParameterSelectService
{
    public function getAllParameters(int $parameterId)
    {
        return ParameterValue::select(['id as value', 'parameter_value as label'])->where('parameter_id', $parameterId)->get();
    }

}

