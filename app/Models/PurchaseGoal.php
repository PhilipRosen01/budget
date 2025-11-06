<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PurchaseGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'target_amount',
        'current_amount',
        'image_url',
        'target_date',
        'priority',
        'is_active',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'target_date' => 'date',
        'priority' => 'integer',
        'is_active' => 'boolean',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the purchase goal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the percentage completed for this goal.
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }
        
        return min(($this->current_amount / $this->target_amount) * 100, 100);
    }

    /**
     * Get the remaining amount needed to complete this goal.
     */
    public function getRemainingAmountAttribute(): float
    {
        return max($this->target_amount - $this->current_amount, 0);
    }

    /**
     * Check if this goal is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->target_date || $this->is_completed) {
            return false;
        }
        
        return Carbon::now()->gt($this->target_date);
    }

    /**
     * Get days until target date.
     */
    public function getDaysUntilTargetAttribute(): ?int
    {
        if (!$this->target_date || $this->is_completed) {
            return null;
        }
        
        return Carbon::now()->diffInDays($this->target_date, false);
    }

    /**
     * Get formatted target date.
     */
    public function getFormattedTargetDateAttribute(): ?string
    {
        return $this->target_date ? $this->target_date->format('M j, Y') : null;
    }

    /**
     * Add money to this goal.
     */
    public function addAmount(float $amount): void
    {
        $this->current_amount += $amount;
        
        // Check if goal is completed
        if ($this->current_amount >= $this->target_amount && !$this->is_completed) {
            $this->is_completed = true;
            $this->completed_at = Carbon::now();
        }
        
        $this->save();
    }

    /**
     * Mark goal as completed.
     */
    public function markAsCompleted(): void
    {
        $this->is_completed = true;
        $this->completed_at = Carbon::now();
        $this->save();
    }

    /**
     * Scope to get active goals.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_completed', false);
    }

    /**
     * Scope to get completed goals.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope to get goals ordered by priority.
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority')->orderBy('created_at');
    }
}
