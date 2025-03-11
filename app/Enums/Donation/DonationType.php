<?php

namespace App\Enums\Donation;

enum DonationType: int{

    case IN_KIND = 1;
    case CASH = 0;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
