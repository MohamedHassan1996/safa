<?php

namespace App\Filters\CharityCase;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Spatie\QueryBuilder\Filters\Filter;

class FilterCharityCaseAge implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        // Ensure $value is numeric
        $age = (int) $value;

        // Calculate date range
        $fromDate = Carbon::now()->subYears($age + 1)->addDay()->startOfDay(); // e.g., 25 years ago + 1 day
        $toDate = Carbon::now()->subYears($age)->endOfDay(); // exactly 25 years ago

        return $query->whereBetween('date_of_birth', [$fromDate, $toDate]);
    }
}
