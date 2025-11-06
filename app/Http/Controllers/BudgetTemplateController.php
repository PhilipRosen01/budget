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
            'amount' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

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
            'amount' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $budgetTemplate->update($validated);

        return redirect()->route('budget-templates.index')->with('success', 'Budget template updated successfully!');
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

        return redirect()->route('budgets.index')->with('success', $message);
    }
}
