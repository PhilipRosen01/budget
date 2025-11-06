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
}
