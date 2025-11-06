<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        
        // Get selected month/year or default to current
        $monthYear = $request->get('month-year');
        if ($monthYear) {
            [$selectedMonth, $selectedYear] = explode('-', $monthYear);
        } else {
            $selectedMonth = $now->month;
            $selectedYear = $now->year;
        }
        $selectedDate = Carbon::create($selectedYear, $selectedMonth, 1);
        $isCurrentMonth = $selectedMonth == $now->month && $selectedYear == $now->year;
        
        // Get selected month's budgets
        $monthBudgets = $user->budgets()
            ->with('purchases')
            ->forMonth($selectedMonth, $selectedYear)
            ->get();
        
        // Generate budgets for current month if none exist and we're viewing current month
        if ($isCurrentMonth && $monthBudgets->isEmpty() && $user->activeBudgetTemplates->isNotEmpty()) {
            foreach ($user->activeBudgetTemplates as $template) {
                $template->createMonthlyBudget($selectedMonth, $selectedYear);
            }
            // Reload budgets after creation
            $monthBudgets = $user->budgets()
                ->with('purchases')
                ->forMonth($selectedMonth, $selectedYear)
                ->get();
        }
        
        // Get recent purchases for selected month
        $recentPurchases = $user->purchases()
            ->with('budget')
            ->whereMonth('purchase_date', $selectedMonth)
            ->whereYear('purchase_date', $selectedYear)
            ->latest()
            ->take(10)
            ->get();
        
        // Calculate total monthly budget and spending
        $totalMonthlyBudget = $monthBudgets->sum('amount');
        
        // Calculate selected month's spending
        $monthlySpending = $user->purchases()
            ->whereMonth('purchase_date', $selectedMonth)
            ->whereYear('purchase_date', $selectedYear)
            ->sum('amount');
        
        // Calculate budget statistics
        $budgetStats = [
            'total_budget' => $totalMonthlyBudget,
            'total_spent' => $monthlySpending,
            'remaining' => $totalMonthlyBudget - $monthlySpending,
            'percentage_used' => $totalMonthlyBudget > 0 ? ($monthlySpending / $totalMonthlyBudget) * 100 : 0,
        ];
        
        // Get available months that have budgets
        $availableMonths = $user->budgets()
            ->selectRaw('DISTINCT month, year')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get()
            ->map(function ($budget) {
                return [
                    'month' => $budget->month,
                    'year' => $budget->year,
                    'display' => Carbon::create($budget->year, $budget->month, 1)->format('F Y'),
                    'value' => $budget->month . '-' . $budget->year
                ];
            });
        
        $selectedMonth = $selectedDate->format('F Y');
        $selectedValue = $selectedDate->month . '-' . $selectedDate->year;
        
        return view('dashboard', compact('monthBudgets', 'recentPurchases', 'budgetStats', 'selectedMonth', 'availableMonths', 'selectedValue', 'isCurrentMonth', 'user'));
    }
}
