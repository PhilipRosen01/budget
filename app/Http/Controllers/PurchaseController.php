<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Auth::user()->purchases()->with('budget')->latest()->get();
        return view('purchases.index', compact('purchases'));
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

        // Verify budget belongs to user if provided
        if ($validated['budget_id']) {
            $budget = Budget::find($validated['budget_id']);
            if ($budget->user_id !== Auth::id()) {
                abort(403);
            }
        }

        Auth::user()->purchases()->create($validated);

        return redirect()->route('purchases.index')->with('success', 'Purchase added successfully!');
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
