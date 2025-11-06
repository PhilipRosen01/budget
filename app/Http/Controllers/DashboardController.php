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
        $now = Carbon::now();
        
        // Get current month's budgets
        $currentMonthBudgets = $user->budgets()
            ->with('purchases')
            ->currentMonth()
            ->get();
        
        // Generate budgets for current month if none exist
        if ($currentMonthBudgets->isEmpty() && $user->activeBudgetTemplates->isNotEmpty()) {
            foreach ($user->activeBudgetTemplates as $template) {
                $template->createMonthlyBudget($now->month, $now->year);
            }
            // Reload budgets after creation
            $currentMonthBudgets = $user->budgets()
                ->with('purchases')
                ->currentMonth()
                ->get();
        }
        
        // Get recent purchases
        $recentPurchases = $user->purchases()
            ->with('budget')
            ->latest()
            ->take(10)
            ->get();
        
        // Calculate total monthly budget and spending
        $totalMonthlyBudget = $currentMonthBudgets->sum('amount');
        
        // Calculate this month's spending
        $monthlySpending = $user->purchases()
            ->whereMonth('purchase_date', $now->month)
            ->whereYear('purchase_date', $now->year)
            ->sum('amount');
        
        // Calculate budget statistics
        $budgetStats = [
            'total_budget' => $totalMonthlyBudget,
            'total_spent' => $monthlySpending,
            'remaining' => $totalMonthlyBudget - $monthlySpending,
            'percentage_used' => $totalMonthlyBudget > 0 ? ($monthlySpending / $totalMonthlyBudget) * 100 : 0,
        ];
        
        $currentMonth = $now->format('F Y');
        
        return view('dashboard', compact('currentMonthBudgets', 'recentPurchases', 'budgetStats', 'currentMonth'));
    }
}
