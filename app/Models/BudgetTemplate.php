<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BudgetTemplate extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'amount',
        'description',
        'category',
        'default_category',
        'is_active',
        'is_automatic',
        'is_auto_amount',
        'percentage',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'percentage' => 'decimal:2',
        'is_active' => 'boolean',
        'is_automatic' => 'boolean',
        'is_auto_amount' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class, 'budget_template_id');
    }

    public function budgetForMonth(int $month, int $year): ?Budget
    {
        return $this->budgets()
            ->where('month', $month)
            ->where('year', $year)
            ->first();
    }

    public function createMonthlyBudget(int $month, int $year): Budget
    {
        // Calculate amount if auto-amount is enabled
        $amount = $this->is_auto_amount ? $this->calculateAutoAmount() : $this->amount;
        
        $budget = $this->budgets()->create([
            'user_id' => $this->user_id,
            'name' => $this->name,
            'amount' => $amount,
            'description' => $this->description,
            'category' => $this->category,
            'month' => $month,
            'year' => $year,
            'is_active' => true,
        ]);

        // For investment budgets, automatically create a purchase to represent the investment allocation
        if ($this->category === 'investments') {
            $budget->purchases()->create([
                'user_id' => $this->user_id,
                'name' => 'Investment Allocation $' . number_format($amount, 0),
                'amount' => $amount,
                'purchase_date' => now()->startOfMonth()->addDays(0), // First day of the month
                'category' => 'investments',
                'notes' => 'Automatic monthly investment allocation - funds allocated to investment accounts',
            ]);
        }

        return $budget;
    }
    
    /**
     * Calculate the auto amount based on salary and percentage
     */
    public function calculateAutoAmount(): float
    {
        $user = $this->user;
        
        if (!$user || !$user->monthly_salary) {
            return $this->amount ?? 0; // Fallback to manual amount
        }
        
        if ($this->percentage) {
            // Use custom percentage
            return round(($user->monthly_salary * $this->percentage) / 100, 2);
        }
        
        // Use default category percentage
        if ($this->default_category) {
            $categories = config('budget_categories.categories');
            
            foreach ($categories as $group => $groupCategories) {
                if (isset($groupCategories[$this->default_category])) {
                    $defaultPercentage = $groupCategories[$this->default_category]['default_percentage'];
                    return round(($user->monthly_salary * $defaultPercentage) / 100, 2);
                }
            }
        }
        
        return $this->amount ?? 0; // Fallback to manual amount
    }
    
    /**
     * Get the calculated amount (either auto or manual)
     */
    public function getCalculatedAmount(): float
    {
        return $this->is_auto_amount ? $this->calculateAutoAmount() : $this->amount;
    }
}
