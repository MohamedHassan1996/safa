<?php

namespace App\Http\Controllers\Api\Private\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class DashboardController extends Controller implements HasMiddleware
{
    public function __construct()
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            //new Middleware('permission:all_stats', only:['index']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $auth = auth()->user();
        $currentUserRole = $auth->getRoleNames()[0];

        $totalUsers = User::count();
        $totalCharityCases = DB::table('charity_cases')->count();
        $allOuterDonations = DB::table('donations')->sum('amount');

        $donations = DB::table('donations')
            ->selectRaw("
                SUM(CASE WHEN `date` = CURDATE() THEN amount ELSE 0 END) AS today_total,
                SUM(CASE WHEN `date` = CURDATE() - INTERVAL 1 DAY THEN amount ELSE 0 END) AS yesterday_total,
                SUM(CASE WHEN YEAR(`date`) = YEAR(CURDATE()) AND MONTH(`date`) = MONTH(CURDATE()) THEN amount ELSE 0 END) AS this_month_total,
                SUM(CASE WHEN YEAR(`date`) = YEAR(CURDATE() - INTERVAL 1 MONTH) AND MONTH(`date`) = MONTH(CURDATE() - INTERVAL 1 MONTH) THEN amount ELSE 0 END) AS last_month_total
            ")
            ->first();

        // Calculate Daily Percentage Change
        $dailyPercentChange = 0;
        if ($donations->yesterday_total > 0) {
            $dailyPercentChange = round((($donations->today_total - $donations->yesterday_total) / $donations->yesterday_total) * 100, 2);
        }

        // Calculate Monthly Percentage Change
        $monthlyPercentChange = 0;
        if ($donations->last_month_total > 0) {
            $monthlyPercentChange = round((($donations->this_month_total - $donations->last_month_total) / $donations->last_month_total) * 100, 2);
        }

        return response()->json([
            'totalUsers' => $totalUsers,
            'totalCharityCases' => $totalCharityCases,
            'allOuterDonations' => $allOuterDonations,
            'dailyOuterDonations' => [
                'todayTotal' => (float) $donations->today_total,
                'yesterdayTotal' => (float) $donations->yesterday_total,
                'percentChange' => $dailyPercentChange,
            ],
            'monthlyOuterDonations' => [
                'thisMonthTotal' => (float) $donations->this_month_total,
                'lastMonthTotal' => (float) $donations->last_month_total,
                'percentChange' => $monthlyPercentChange,
            ],
        ]);


    }

}
