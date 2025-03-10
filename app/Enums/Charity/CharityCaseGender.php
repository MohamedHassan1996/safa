<?php

namespace App\Enums\Charity;

enum CharityCaseGender: int{

    case FEMALE = 1;
    case MALE = 0;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
