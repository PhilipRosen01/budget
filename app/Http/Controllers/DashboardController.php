<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get active budgets
        $activeBudgets = $user->activeBudgets()->with('purchases')->get();
        
        // Get recent purchases
        $recentPurchases = $user->purchases()
            ->with('budget')
            ->latest()
            ->take(10)
            ->get();
        
        // Calculate total monthly budget and spending
        $monthlyBudgets = $activeBudgets->where('period', 'monthly');
        $yearlyBudgets = $activeBudgets->where('period', 'yearly');
        
        $totalMonthlyBudget = $monthlyBudgets->sum('amount') + ($yearlyBudgets->sum('amount') / 12);
        
        // Calculate this month's spending
        $currentMonth = Carbon::now()->startOfMonth();
        $monthlySpending = $user->purchases()
            ->where('purchase_date', '>=', $currentMonth)
            ->sum('amount');
        
        // Calculate budget statistics
        $budgetStats = [
            'total_budget' => $totalMonthlyBudget,
            'total_spent' => $monthlySpending,
            'remaining' => $totalMonthlyBudget - $monthlySpending,
            'percentage_used' => $totalMonthlyBudget > 0 ? ($monthlySpending / $totalMonthlyBudget) * 100 : 0,
        ];
        
        return view('dashboard', compact('activeBudgets', 'recentPurchases', 'budgetStats'));
    }
}
