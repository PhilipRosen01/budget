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
        
        // Don't auto-generate budgets if we just deleted them (check for success message)
        // Only auto-generate budgets for current month if none exist and we're viewing current month
        // and we're not coming from a delete operation
        if ($isCurrentMonth && $monthBudgets->isEmpty() && $user->activeBudgetTemplates->isNotEmpty() && !session('success')) {
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
        
        // Separate investment and regular budgets
        $investmentBudgets = $monthBudgets->where('category', 'investments');
        $regularBudgets = $monthBudgets->where('category', '!=', 'investments');
        
        // Calculate totals - get actual salary and investment from preferences, not from budget sums
        $totalSalary = $user->monthly_salary ?? 0;
        $budgetPreferences = $user->budgetPreferences;
        $investmentAmount = ($budgetPreferences && $budgetPreferences->auto_invest_enabled) 
            ? (float) $budgetPreferences->monthly_investment_amount 
            : 0;
        $availableBudget = $totalSalary - $investmentAmount;
        
        // Calculate spending (excluding investment allocations which are automatic)
        $monthlySpending = $user->purchases()
            ->whereMonth('purchase_date', $selectedMonth)
            ->whereYear('purchase_date', $selectedYear)
            ->where('category', '!=', 'investments') // Exclude automatic investment purchases
            ->sum('amount');
        
        // Calculate budget statistics
        $budgetStats = [
            'total_salary' => $totalSalary,
            'investment_amount' => $investmentAmount,
            'available_budget' => $availableBudget,
            'total_budget' => $availableBudget, // For backward compatibility with views
            'total_spent' => $monthlySpending,
            'remaining' => $availableBudget - $monthlySpending,
            'percentage_used' => $availableBudget > 0 ? ($monthlySpending / $availableBudget) * 100 : 0,
        ];
        
        // Get purchase goals
        $purchaseGoals = $user->purchaseGoals()
            ->orderBy('priority')
            ->orderBy('created_at')
            ->take(5)
            ->get();

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
        
        // Check if this is an empty state (no budgets for any month)
        $hasAnyBudgets = $user->budgets()->exists();
        $activeTemplates = $user->activeBudgetTemplates;
        
        $selectedMonth = $selectedDate->format('F Y');
        $selectedValue = $selectedDate->month . '-' . $selectedDate->year;
        
        return view('dashboard', compact('monthBudgets', 'recentPurchases', 'budgetStats', 'selectedMonth', 'availableMonths', 'selectedValue', 'isCurrentMonth', 'user', 'purchaseGoals', 'hasAnyBudgets', 'activeTemplates'));
    }
}
