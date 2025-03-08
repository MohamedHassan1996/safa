<?php

namespace App\Enums\Charity;

enum CharityStatus: int{

    case ACTIVE = 1;
    case INACTIVE = 0;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
