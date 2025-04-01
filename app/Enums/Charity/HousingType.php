<?php

namespace App\Enums\Charity;

enum HousingType: int{

    case RENT
    = 1;
    case OWN
    = 0;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
