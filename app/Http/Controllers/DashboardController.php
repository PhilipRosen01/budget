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
        
        // Check if user has ANY budgets first
        $hasAnyBudgets = $user->budgets()->exists();
        
        // Get selected month/year or default to current
        $monthYear = $request->get('month-year');
        if ($monthYear) {
            [$selectedMonth, $selectedYear] = explode('-', $monthYear);
        } else {
            // If no budgets exist at all, still show current month but in empty state
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
        
        // NEVER auto-generate budgets if:
        // 1. We have a success message (just deleted budgets)
        // 2. User explicitly came from a delete operation
        // 3. User has no budgets at all (clean slate state)
        $shouldAutoGenerate = $isCurrentMonth 
            && $monthBudgets->isEmpty() 
            && $user->activeBudgetTemplates->isNotEmpty() 
            && !session('success')
            && !session()->has('just_deleted')
            && $hasAnyBudgets; // Only auto-generate if user has budgets in other months
            
        if ($shouldAutoGenerate) {
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

        // Get available months that have budgets (refresh from database to avoid stale data)
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

        // Get active templates for view
        $activeTemplates = $user->activeBudgetTemplates;
        
        $selectedMonth = $selectedDate->format('F Y');
        $selectedValue = $selectedDate->month . '-' . $selectedDate->year;
        
        // If the selected month doesn't exist in available months and isn't current month,
        // redirect to current month or first available month
        $selectedExists = $availableMonths->contains('value', $selectedValue);
        if (!$selectedExists && !$isCurrentMonth && $availableMonths->isNotEmpty()) {
            $firstAvailable = $availableMonths->first();
            return redirect()->route('dashboard', ['month-year' => $firstAvailable['value']]);
        }
        
        // Clear the just_deleted flag after one dashboard load
        // This prevents infinite auto-generation suppression
        if (session()->has('just_deleted')) {
            session()->forget('just_deleted');
        }
        
        return view('dashboard', compact('monthBudgets', 'recentPurchases', 'budgetStats', 'selectedMonth', 'availableMonths', 'selectedValue', 'isCurrentMonth', 'user', 'purchaseGoals', 'hasAnyBudgets', 'activeTemplates'));
    }
}
