<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'amount',
        'period',
        'start_date',
        'end_date',
        'description',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
}
