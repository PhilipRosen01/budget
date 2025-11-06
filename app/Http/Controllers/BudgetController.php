<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\BudgetTemplate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BudgetController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        
        $budgets = Auth::user()->budgets()
            ->with(['purchases', 'budgetTemplate'])
            ->forMonth($month, $year)
            ->get();
            
        $currentDate = Carbon::create($year, $month, 1);
        $availableMonths = $this->getAvailableMonths();
        
        return view('budgets.index', compact('budgets', 'currentDate', 'availableMonths', 'month', 'year'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        $templates = Auth::user()->activeBudgetTemplates;
        
        return view('budgets.create', compact('month', 'year', 'templates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2050',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = true;

        Budget::create($validated);

        return redirect()->route('budgets.index', ['month' => $validated['month'], 'year' => $validated['year']])
            ->with('success', 'Budget created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        // Simple check - user can only view their own budgets
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }
        
        $budget->load('purchases');
        return view('budgets.show', compact('budget'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('budgets.edit', compact('budget'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'is_active' => 'boolean',
        ]);

        $budget->update($validated);

        return redirect()->route('budgets.index', ['month' => $budget->month, 'year' => $budget->year])
            ->with('success', 'Budget updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }
        
        $budget->delete();

        return redirect()->route('budgets.index')->with('success', 'Budget deleted successfully!');
    }

    /**
     * Delete all budgets for a specific month and year
     */
    public function destroyMonth(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        $user = Auth::user();
        $month = $validated['month'];
        $year = $validated['year'];
        
        $monthName = Carbon::create($year, $month, 1)->format('F Y');

        // Store deletion parameters in session for background processing
        session([
            'pending_deletion' => [
                'month' => $month,
                'year' => $year,
                'month_name' => $monthName
            ]
        ]);

        // Redirect to setup page immediately - this ensures user sees the fresh dashboard
        $now = Carbon::now();
        return redirect()->route('budgets.setup', [
            'month' => $now->month,
            'year' => $now->year
        ])->with('info', "Redirecting to budget setup. Deleting {$monthName} budgets...");
    }
    
    public function completeDeletion(Request $request)
    {
        $pendingDeletion = session('pending_deletion');
        
        if (!$pendingDeletion) {
            return redirect()->route('dashboard')->with('error', 'No pending deletion found.');
        }
        
        $user = Auth::user();
        $month = $pendingDeletion['month'];
        $year = $pendingDeletion['year'];
        $monthName = $pendingDeletion['month_name'];

        // Delete all budgets for this month
        $deletedCount = $user->budgets()
            ->where('month', $month)
            ->where('year', $year)
            ->delete();

        // Also delete all purchases for this month (they'll be orphaned otherwise)
        $user->purchases()
            ->whereMonth('purchase_date', $month)
            ->whereYear('purchase_date', $year)
            ->delete();

        // Clear the pending deletion from session
        session()->forget('pending_deletion');
        
        if ($deletedCount > 0) {
            $message = "Successfully deleted all budgets for {$monthName} ({$deletedCount} budgets removed).";
            return redirect()->route('dashboard')->with('success', $message);
        } else {
            return redirect()->route('dashboard')->with('info', "No budgets found for {$monthName}.");
        }
    }

    private function getAvailableMonths()
    {
        // Get months that have budgets for the current user
        $budgets = Auth::user()->budgets()
            ->selectRaw('DISTINCT month, year')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $months = [];
        foreach ($budgets as $budget) {
            $date = Carbon::create($budget->year, $budget->month, 1);
            $months[] = [
                'month' => $budget->month,
                'year' => $budget->year,
                'display' => $date->format('F Y')
            ];
        }

        // Always include current month
        $now = Carbon::now();
        $currentMonthExists = collect($months)->contains(function ($month) use ($now) {
            return $month['month'] == $now->month && $month['year'] == $now->year;
        });

        if (!$currentMonthExists) {
            array_unshift($months, [
                'month' => $now->month,
                'year' => $now->year,
                'display' => $now->format('F Y')
            ]);
        }

        return $months;
    }

    /**
     * Create budgets from all active templates for the current month
     */
    public function createFromTemplates(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $month = $request->get('month', $now->month);
        $year = $request->get('year', $now->year);
        
        $createdCount = 0;
        
        foreach ($user->activeBudgetTemplates as $template) {
            // Check if budget already exists for this template/month
            $existingBudget = $template->budgetForMonth($month, $year);
            
            if (!$existingBudget) {
                $template->createMonthlyBudget($month, $year);
                $createdCount++;
            }
        }
        
        $monthName = Carbon::create($year, $month, 1)->format('F Y');
        
        if ($createdCount > 0) {
            return redirect()->route('dashboard', ['month-year' => $month . '-' . $year])
                ->with('success', "Created {$createdCount} budgets for {$monthName} from your active templates!");
        } else {
            return redirect()->route('dashboard', ['month-year' => $month . '-' . $year])
                ->with('info', "All budgets for {$monthName} already exist.");
        }
    }

    /**
     * Show form to manually select templates for budget creation
     */
    public function selectTemplates(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $month = $request->get('month', $now->month);
        $year = $request->get('year', $now->year);
        
        $templates = $user->budgetTemplates()->where('is_active', true)->get();
        $monthName = Carbon::create($year, $month, 1)->format('F Y');
        
        // Get which templates already have budgets for this month
        $existingBudgets = $user->budgets()
            ->where('month', $month)
            ->where('year', $year)
            ->pluck('budget_template_id')
            ->toArray();
        
        return view('budgets.select-templates', compact('templates', 'month', 'year', 'monthName', 'existingBudgets'));
    }

    /**
     * Create budgets from selected templates
     */
    public function createFromSelectedTemplates(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'template_ids' => 'required|array',
            'template_ids.*' => 'exists:budget_templates,id',
        ]);

        $user = Auth::user();
        $month = $validated['month'];
        $year = $validated['year'];
        $templateIds = $validated['template_ids'];
        
        $createdCount = 0;
        
        foreach ($templateIds as $templateId) {
            $template = $user->budgetTemplates()->find($templateId);
            
            if ($template) {
                // Check if budget already exists
                $existingBudget = $template->budgetForMonth($month, $year);
                
                if (!$existingBudget) {
                    $template->createMonthlyBudget($month, $year);
                    $createdCount++;
                }
            }
        }
        
        $monthName = Carbon::create($year, $month, 1)->format('F Y');
        
        return redirect()->route('dashboard', ['month-year' => $month . '-' . $year])
            ->with('success', "Created {$createdCount} budgets for {$monthName} from selected templates!");
    }

    /**
     * Show enhanced budget setup page with manual/automatic options
     */
    public function setupBudgets(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $month = $request->get('month', $now->month);
        $year = $request->get('year', $now->year);
        
        $monthName = Carbon::create($year, $month, 1)->format('F Y');
        $budgetPreferences = $user->budgetPreferences;
        $totalSalary = $user->monthly_salary ?? 0;
        
        // Get investment amount
        $investmentAmount = ($budgetPreferences && $budgetPreferences->auto_invest_enabled) 
            ? (float) $budgetPreferences->monthly_investment_amount 
            : 0;
        $availableBudget = $totalSalary - $investmentAmount;
        
        // Get all active templates
        $templates = $user->budgetTemplates()->where('is_active', true)->get();
        
        // Get templates that would be used in automatic setup based on preferences
        $automaticTemplates = $templates;
        if ($budgetPreferences) {
            $automaticTemplates = $templates->filter(function($template) use ($budgetPreferences) {
                return $this->shouldIncludeTemplateInAutomatic($template, $budgetPreferences);
            });
        }
        
        // Get existing budgets for this month
        $existingBudgets = $user->budgets()
            ->where('month', $month)
            ->where('year', $year)
            ->pluck('budget_template_id')
            ->toArray();
        
        return view('budgets.setup', compact(
            'month', 'year', 'monthName', 'totalSalary', 'investmentAmount', 
            'availableBudget', 'templates', 'automaticTemplates', 'existingBudgets',
            'budgetPreferences'
        ));
    }

    /**
     * Create budgets automatically based on user preferences
     */
    public function createAutomaticBudgets(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        $user = Auth::user();
        $month = $validated['month'];
        $year = $validated['year'];
        $budgetPreferences = $user->budgetPreferences;
        
        if (!$budgetPreferences) {
            return redirect()->route('dashboard')
                ->with('error', 'Please set up your budget preferences first.');
        }

        $createdCount = 0;
        $templates = $user->budgetTemplates()->where('is_active', true)->get();
        
        foreach ($templates as $template) {
            // Check if this template should be included based on preferences
            if ($this->shouldIncludeTemplateInAutomatic($template, $budgetPreferences)) {
                $existingBudget = $template->budgetForMonth($month, $year);
                
                if (!$existingBudget) {
                    $template->createMonthlyBudget($month, $year);
                    $createdCount++;
                }
            }
        }
        
        $monthName = Carbon::create($year, $month, 1)->format('F Y');
        
        return redirect()->route('dashboard', ['month-year' => $month . '-' . $year])
            ->with('success', "Automatically created {$createdCount} budgets for {$monthName} based on your preferences!");
    }

    /**
     * Determine if a template should be included in automatic setup based on user preferences
     */
    private function shouldIncludeTemplateInAutomatic($template, $budgetPreferences)
    {
        $category = strtolower($template->category ?? '');
        
        // Check user's preference exemptions
        switch ($category) {
            case 'housing':
                return !$budgetPreferences->no_rent;
            case 'transportation':
                return !($budgetPreferences->no_car_payment && $budgetPreferences->no_gas && $budgetPreferences->no_maintenance);
            case 'food':
                return !$budgetPreferences->no_groceries;
            case 'insurance':
                return !$budgetPreferences->no_insurance;
            case 'utilities':
                return !($budgetPreferences->no_phone_payment && $budgetPreferences->no_utilities && $budgetPreferences->no_internet);
            case 'miscellaneous':
                return !$budgetPreferences->no_subscriptions;
            case 'investments':
                return $budgetPreferences->auto_invest_enabled;
            default:
                // Include other categories by default (savings, debt, personal, etc.)
                return true;
        }
    }
}
