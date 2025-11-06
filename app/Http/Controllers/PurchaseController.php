<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->purchases()->with('budget');
        
        // Month and year filtering
        $selectedMonth = $request->get('month', now()->month);
        $selectedYear = $request->get('year', now()->year);
        
        if ($selectedMonth && $selectedYear) {
            $query->whereMonth('purchase_date', $selectedMonth)
                  ->whereYear('purchase_date', $selectedYear);
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'purchase_date');
        $sortDirection = $request->get('direction', 'desc');
        
        $allowedSorts = ['purchase_date', 'amount', 'name', 'category'];
        if (in_array($sortBy, $allowedSorts)) {
            if ($sortBy === 'purchase_date') {
                $query->orderBy('purchase_date', $sortDirection);
            } else {
                $query->orderBy($sortBy, $sortDirection);
            }
        } else {
            $query->latest('purchase_date');
        }
        
        $purchases = $query->get();
        
        // Get available months for dropdown (SQLite compatible)
        $availableMonths = $user->purchases()
            ->selectRaw("DISTINCT strftime('%m', purchase_date) as month, strftime('%Y', purchase_date) as year")
            ->whereNotNull('purchase_date')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get()
            ->map(function ($purchase) {
                return [
                    'month' => (int)$purchase->month,
                    'year' => (int)$purchase->year,
                    'display' => Carbon::create($purchase->year, $purchase->month, 1)->format('F Y'),
                ];
            });
        
        return view('purchases.index', compact('purchases', 'availableMonths', 'selectedMonth', 'selectedYear', 'sortBy', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $budgets = Auth::user()->activeBudgets()->get();
        return view('purchases.create', compact('budgets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'budget_id' => 'nullable|exists:budgets,id',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'purchase_date' => 'required|date',
        ]);

        // Verify budget belongs to user if provided and set category from budget if not specified
        if ($validated['budget_id']) {
            $budget = Budget::find($validated['budget_id']);
            if ($budget->user_id !== Auth::id()) {
                abort(403);
            }
            
            // If no category is specified, use the budget's category
            if (empty($validated['category']) && $budget->category) {
                $validated['category'] = $budget->category;
            }
        }

        Auth::user()->purchases()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Purchase added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        if ($purchase->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        if ($purchase->user_id !== Auth::id()) {
            abort(403);
        }
        
        $budgets = Auth::user()->activeBudgets()->get();
        return view('purchases.edit', compact('purchase', 'budgets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        if ($purchase->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'budget_id' => 'nullable|exists:budgets,id',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'purchase_date' => 'required|date',
        ]);

        // Verify budget belongs to user if provided
        if ($validated['budget_id']) {
            $budget = Budget::find($validated['budget_id']);
            if ($budget->user_id !== Auth::id()) {
                abort(403);
            }
        }

        $purchase->update($validated);

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        if ($purchase->user_id !== Auth::id()) {
            abort(403);
        }
        
        $purchase->delete();

        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully!');
    }
}
