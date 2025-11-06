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
        'is_active',
        'is_automatic',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_automatic' => 'boolean',
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
        return $this->budgets()->create([
            'user_id' => $this->user_id,
            'name' => $this->name,
            'amount' => $this->amount,
            'description' => $this->description,
            'category' => $this->category,
            'month' => $month,
            'year' => $year,
            'is_active' => true,
        ]);
    }
}
