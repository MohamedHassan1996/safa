<?php

namespace App\Services\Select\Parameter;

use App\Models\Parameter\ParameterValue;

class ParameterSelectService
{
    public function getAllParameters(int $parameterId)
    {
        return ParameterValue::select(['id as value', 'parameter_value as label'])->where('parameter_id', $parameterId)->get();
    }

    public function getAllParametersWithColor(int $parameterId)
    {
        $parameters = ParameterValue::select(['id as value', 'parameter_value as label', 'color'])->where('parameter_id', $parameterId)->get();

        $parametersData = [];

        foreach ($parameters as $parameter) {
            $parametersData[] = [
                'value' => $parameter->value,
                'label' => [
                    'label' => $parameter->label,
                    'color' => $parameter->color
                ],
            ];
        }

        return $parametersData;
    }


}

