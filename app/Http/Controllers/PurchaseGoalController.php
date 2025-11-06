<?php

namespace App\Http\Controllers;

use App\Models\PurchaseGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseGoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $purchaseGoals = $user->purchaseGoals()
            ->orderBy('is_completed')
            ->orderBy('priority')
            ->orderBy('created_at')
            ->get();
        
        return view('purchase-goals.index', compact('purchaseGoals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('purchase-goals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0.01|max:999999.99',
            'target_date' => 'nullable|date|after:today',
            'priority' => 'required|integer|min:1|max:10',
            'image_url' => 'nullable|url|max:500',
        ]);

        Auth::user()->purchaseGoals()->create($validated);

        return redirect()->route('purchase-goals.index')->with('success', 'Purchase goal created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseGoal $purchaseGoal)
    {
        if ($purchaseGoal->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('purchase-goals.show', compact('purchaseGoal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseGoal $purchaseGoal)
    {
        if ($purchaseGoal->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('purchase-goals.edit', compact('purchaseGoal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseGoal $purchaseGoal)
    {
        if ($purchaseGoal->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0.01',
            'priority' => 'required|integer|min:1|max:10',
        ]);

        $purchaseGoal->update($validated);

        return redirect()->route('purchase-goals.index')
            ->with('success', 'Purchase goal updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseGoal $purchaseGoal)
    {
        if ($purchaseGoal->user_id !== Auth::id()) {
            abort(403);
        }

        $purchaseGoal->delete();

        return redirect()->route('purchase-goals.index')
            ->with('success', 'Purchase goal deleted successfully!');
    }
}
