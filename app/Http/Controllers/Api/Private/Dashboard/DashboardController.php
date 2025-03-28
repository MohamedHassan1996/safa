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


        $dailyDonations = DB::table('donations')
        ->selectRaw("
            SUM(CASE WHEN `date` = CURDATE() THEN amount ELSE 0 END) AS today_total,
            SUM(CASE WHEN `date` = CURDATE() - INTERVAL 1 DAY THEN amount ELSE 0 END) AS yesterday_total,
            IF(SUM(CASE WHEN `date` = CURDATE() - INTERVAL 1 DAY THEN amount ELSE 0 END) > 0,
                ROUND(((SUM(CASE WHEN `date` = CURDATE() THEN amount ELSE 0 END) - SUM(CASE WHEN `date` = CURDATE() - INTERVAL 1 DAY THEN amount ELSE 0 END)) / SUM(CASE WHEN `date` = CURDATE() - INTERVAL 1 DAY THEN amount ELSE 0 END)) * 100, 2),
                0
            ) AS percent_change
        ")
        ->first();

        $monthlyDonations = DB::table('donations')
        ->selectRaw("
            SUM(CASE WHEN MONTH(`date`) = MONTH(CURDATE()) AND YEAR(`date`) = YEAR(CURDATE()) THEN amount ELSE 0 END) AS this_month_total,
            SUM(CASE WHEN MONTH(`date`) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(`date`) = YEAR(CURDATE() - INTERVAL 1 MONTH) THEN amount ELSE 0 END) AS last_month_total,
            IF(SUM(CASE WHEN MONTH(`date`) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(`date`) = YEAR(CURDATE() - INTERVAL 1 MONTH) THEN amount ELSE 0 END) > 0,
                ROUND(((SUM(CASE WHEN MONTH(`date`) = MONTH(CURDATE()) AND YEAR(`date`) = YEAR(CURDATE()) THEN amount ELSE 0 END) - SUM(CASE WHEN MONTH(`date`) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(`date`) = YEAR(CURDATE() - INTERVAL 1 MONTH) THEN amount ELSE 0 END)) / SUM(CASE WHEN MONTH(`date`) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(`date`) = YEAR(CURDATE() - INTERVAL 1 MONTH) THEN amount ELSE 0 END)) * 100, 2),
                0
            ) AS percent_change
        ")
        ->first();

        return response()->json([
            'totalUsers' => $totalUsers,
            'totalCharityCases' => $totalCharityCases,
            'allOuterDonations' => $allOuterDonations,
            'dailyOuterDonations' => $dailyDonations,
            'monthlyOuterDonations' => $monthlyDonations,
        ]);


    }

}
