<?php

namespace App\Enums\Charity;

enum CharityCaseSocialStatus: int{
    case SINGLE = 0;
    case MARRIED = 1;
    case WIDOWED = 2;
    case DIVORCED = 3;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
