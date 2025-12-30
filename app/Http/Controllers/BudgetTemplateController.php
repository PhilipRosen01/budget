<?php

namespace App\Http\Controllers;

use App\Models\BudgetTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BudgetTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = Auth::user()->budgetTemplates()->latest()->get();
        return view('budget-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('budget-templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'default_category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_auto_amount' => 'boolean',
            'percentage' => 'nullable|numeric|min:0|max:100',
        ]);
        
        // Set defaults
        $validated['is_auto_amount'] = $request->has('is_auto_amount');
        
        // If auto amount, amount is not required but percentage is
        if ($validated['is_auto_amount']) {
            if (!$validated['percentage'] && !$validated['default_category']) {
                return back()->withErrors(['percentage' => 'Percentage is required when auto-calculate is enabled.'])->withInput();
            }
            // Set a placeholder amount, will be calculated on budget creation
            $validated['amount'] = 0;
        } else {
            // Manual amount required
            if (!$validated['amount']) {
                return back()->withErrors(['amount' => 'Amount is required when auto-calculate is disabled.'])->withInput();
            }
        }

        $template = Auth::user()->budgetTemplates()->create($validated);

        // Generate budget for current month if it doesn't exist
        $now = Carbon::now();
        $existingBudget = $template->budgetForMonth($now->month, $now->year);
        
        if (!$existingBudget) {
            $template->createMonthlyBudget($now->month, $now->year);
        }

        return redirect()->route('budget-templates.index')->with('success', 'Budget template created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BudgetTemplate $budgetTemplate)
    {
        if ($budgetTemplate->user_id !== Auth::id()) {
            abort(403);
        }
        
        $budgetTemplate->load('budgets.purchases');
        return view('budget-templates.show', compact('budgetTemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BudgetTemplate $budgetTemplate)
    {
        if ($budgetTemplate->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('budget-templates.edit', compact('budgetTemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BudgetTemplate $budgetTemplate)
    {
        if ($budgetTemplate->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'default_category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_auto_amount' => 'boolean',
            'percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        // Handle checkboxes
        $validated['is_active'] = $request->has('is_active');
        $validated['is_auto_amount'] = $request->has('is_auto_amount');
        
        // Validation for auto amount
        if ($validated['is_auto_amount']) {
            if (!$validated['percentage'] && !$validated['default_category']) {
                return back()->withErrors(['percentage' => 'Percentage is required when auto-calculate is enabled.'])->withInput();
            }
            $validated['amount'] = 0;
        } else {
            if (!$validated['amount']) {
                return back()->withErrors(['amount' => 'Amount is required when auto-calculate is disabled.'])->withInput();
            }
        }

        try {
            $budgetTemplate->update($validated);
            return redirect()->route('budget-templates.index')->with('success', 'Budget template updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update template: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BudgetTemplate $budgetTemplate)
    {
        if ($budgetTemplate->user_id !== Auth::id()) {
            abort(403);
        }
        
        $budgetTemplate->delete();

        return redirect()->route('budget-templates.index')->with('success', 'Budget template deleted successfully!');
    }

    /**
     * Bulk delete multiple budget templates
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:budget_templates,id',
        ]);

        $user = Auth::user();
        
        // Ensure all templates belong to the authenticated user
        $templates = BudgetTemplate::whereIn('id', $validated['ids'])
            ->where('user_id', $user->id)
            ->get();

        if ($templates->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No templates found or you do not have permission to delete them.'
            ], 403);
        }

        $count = $templates->count();
        
        // Delete all selected templates
        BudgetTemplate::whereIn('id', $validated['ids'])
            ->where('user_id', $user->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "{$count} template(s) deleted successfully!",
            'count' => $count
        ]);
    }

    /**
     * Generate budgets for next month
     */
    public function generateNextMonth()
    {
        $nextMonth = Carbon::now()->addMonth();
        $user = Auth::user();
        $generatedCount = 0;

        foreach ($user->activeBudgetTemplates as $template) {
            $existingBudget = $template->budgetForMonth($nextMonth->month, $nextMonth->year);
            
            if (!$existingBudget) {
                $template->createMonthlyBudget($nextMonth->month, $nextMonth->year);
                $generatedCount++;
            }
        }

        $message = $generatedCount > 0 
            ? "Generated {$generatedCount} budgets for " . $nextMonth->format('F Y')
            : "All budgets for " . $nextMonth->format('F Y') . " already exist";

        return redirect()->route('budgets.index')->with('success', $message);
    }

    /**
     * Generate budgets for current month
     */
    public function generateCurrentMonth()
    {
        $currentMonth = Carbon::now();
        $user = Auth::user();
        $generatedCount = 0;

        foreach ($user->activeBudgetTemplates as $template) {
            $existingBudget = $template->budgetForMonth($currentMonth->month, $currentMonth->year);
            
            if (!$existingBudget) {
                $template->createMonthlyBudget($currentMonth->month, $currentMonth->year);
                $generatedCount++;
            }
        }

        $message = $generatedCount > 0 
            ? "Generated {$generatedCount} budgets for " . $currentMonth->format('F Y')
            : "All budgets for " . $currentMonth->format('F Y') . " already exist";

        return redirect()->route('budget-templates.index')->with('success', $message);
    }
    
    /**
     * Show form to generate budgets for specific month
     */
    public function showGenerateForm()
    {
        $templates = Auth::user()->activeBudgetTemplates;
        return view('budget-templates.generate', compact('templates'));
    }
    
    /**
     * Generate budgets for specified month/year
     */
    public function generateForMonth(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
        ]);
        
        $user = Auth::user();
        $generatedCount = 0;
        $month = $validated['month'];
        $year = $validated['year'];

        foreach ($user->activeBudgetTemplates as $template) {
            $existingBudget = $template->budgetForMonth($month, $year);
            
            if (!$existingBudget) {
                $template->createMonthlyBudget($month, $year);
                $generatedCount++;
            }
        }

        $monthName = Carbon::create($year, $month, 1)->format('F Y');
        $message = $generatedCount > 0 
            ? "Generated {$generatedCount} budgets for {$monthName}"
            : "All budgets for {$monthName} already exist";

        return redirect()->route('budgets.index')->with('success', $message);
    }
}
