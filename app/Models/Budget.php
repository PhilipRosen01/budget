<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Budget extends Model
{
    protected $fillable = [
        'user_id',
        'budget_template_id',
        'name',
        'amount',
        'description',
        'category',
        'icon',
        'month',
        'year',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'month' => 'integer',
        'year' => 'integer',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function budgetTemplate(): BelongsTo
    {
        return $this->belongsTo(BudgetTemplate::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function totalSpent(): float
    {
        return $this->purchases()->sum('amount');
    }

    public function remainingBudget(): float
    {
        return $this->amount - $this->totalSpent();
    }

    public function percentageUsed(): float
    {
        if ($this->amount == 0) return 0;
        return ($this->totalSpent() / $this->amount) * 100;
    }

    public function getMonthNameAttribute(): string
    {
        return Carbon::create($this->year, $this->month, 1)->format('F');
    }

    public function getFullMonthYearAttribute(): string
    {
        return Carbon::create($this->year, $this->month, 1)->format('F Y');
    }

    public function isCurrentMonth(): bool
    {
        $now = Carbon::now();
        return $this->month === $now->month && $this->year === $now->year;
    }

    public function scopeForMonth($query, int $month, int $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    public function scopeCurrentMonth($query)
    {
        $now = Carbon::now();
        return $query->forMonth($now->month, $now->year);
    }

    /**
     * Get default icon based on category name if no icon is set
     */
    public function getIconAttribute($value): string
    {
        // Return the stored icon if it exists
        if ($value) {
            return $value;
        }

        // Otherwise, provide smart defaults based on category/name
        $name = strtolower($this->name ?? '');
        $category = strtolower($this->category ?? '');

        // Category-based defaults
        $categoryIcons = [
            'food' => '🍔',
            'groceries' => '🛒',
            'dining' => '🍽️',
            'transportation' => '🚗',
            'gas' => '⛽',
            'utilities' => '💡',
            'entertainment' => '🎬',
            'shopping' => '🛍️',
            'health' => '🏥',
            'fitness' => '💪',
            'education' => '📚',
            'travel' => '✈️',
            'rent' => '🏠',
            'insurance' => '🛡️',
            'savings' => '💰',
            'investments' => '📈',
            'phone' => '📱',
            'internet' => '🌐',
            'clothing' => '👕',
            'personal' => '👤',
            'pets' => '🐾',
            'gifts' => '🎁',
            'subscriptions' => '📺',
        ];

        // Check category first
        foreach ($categoryIcons as $key => $icon) {
            if (str_contains($category, $key) || str_contains($name, $key)) {
                return $icon;
            }
        }

        // Default fallback
        return '💰';
    }
}
