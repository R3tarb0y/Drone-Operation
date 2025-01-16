<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Estimation;
use App\Models\Requests;
use App\Models\Receive;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(HttpRequest $request)
    {
        $year = $request->input('year', Carbon::now()->year);

        // Hitung data utama
        $totalRequests = Requests::whereYear('created_at', $year)->count();
        $totalReceives = Receive::whereYear('created_at', $year)->count();
        $totalReports = Report::whereYear('created_at', $year)->count();
        $totalMaintenance = Estimation::whereYear('created_at', $year)->count();
        
        // Total Budget
        $totalCapex = Budget::where('jenis_budget', 'Capex')
                            ->whereYear('created_at', $year)
                            ->sum('total_budget');
        $totalOpex = Budget::where('jenis_budget', 'Opex')
                            ->whereYear('created_at', $year)
                            ->sum('total_budget');
        
        // Total Transaksi Digunakan
        $totalCapexUsed = Requests::where('sumberdana', 'Capex')
                                  ->whereYear('created_at', $year)
                                  ->sum('price');
        $totalOpexUsed = Requests::where('sumberdana', 'Opex')
                                  ->whereYear('created_at', $year)
                                  ->sum('price');
        
        // Sisa Budget
        $totalCapexRemaining = $totalCapex - $totalCapexUsed;
        $totalOpexRemaining = $totalOpex - $totalOpexUsed;
        
        // Revenue Sources
        $revenueSources = [
            'CapexBudget' => $totalCapex,
            'CapexUsed' => $totalCapexUsed,
            'CapexRemaining' => $totalCapexRemaining,
            'OpexBudget' => $totalOpex,
            'OpexUsed' => $totalOpexUsed,
            'OpexRemaining' => $totalOpexRemaining,
        ];
        
        // Persentase
        $percentageCapex = $totalCapex ? ($totalCapexUsed / $totalCapex) * 100 : 0;
        $percentageOpex = $totalOpex ? ($totalOpexUsed / $totalOpex) * 100 : 0;
        
        // Earnings Overview
        $earningsData = Requests::selectRaw('SUM(price) as total, MONTH(created_at) as month')
                                ->whereIn('sumberdana', ['Capex', 'Opex'])
                                ->whereYear('created_at', $year)
                                ->groupBy('month')
                                ->orderBy('month')
                                ->get();

        $earningsCapex = Requests::selectRaw('SUM(price) as total, MONTH(created_at) as month')
                ->where('sumberdana', 'Capex')
                ->whereYear('created_at', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

        $earningsOpex = Requests::selectRaw('SUM(price) as total, MONTH(created_at) as month')
                ->where('sumberdana', 'Opex')
                ->whereYear('created_at', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->get(); 

        return view('dashboard', compact(
            'totalRequests',
            'totalReceives',
            'totalReports',
            'totalMaintenance',
            'earningsData',
            'revenueSources',
            'earningsCapex',
            'earningsOpex',
            'percentageCapex',
            'percentageOpex',
            'year',
            'totalCapexUsed',
            'totalOpexUsed',
            'totalCapexRemaining',
            'totalOpexRemaining',
            'totalCapex',
            'totalOpex'
        )); 
    }
}
