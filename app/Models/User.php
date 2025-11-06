<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'monthly_salary',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'monthly_salary' => 'decimal:2',
        ];
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    public function budgetTemplates(): HasMany
    {
        return $this->hasMany(BudgetTemplate::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function activeBudgets(): HasMany
    {
        return $this->hasMany(Budget::class)->where('is_active', true);
    }

    public function activeBudgetTemplates(): HasMany
    {
        return $this->hasMany(BudgetTemplate::class)->where('is_active', true);
    }

    public function currentMonthBudgets(): HasMany
    {
        $now = \Carbon\Carbon::now();
        return $this->hasMany(Budget::class)
            ->where('month', $now->month)
            ->where('year', $now->year);
    }

    public function budgetPreferences(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BudgetPreference::class);
    }

    /**
     * Get or create budget preferences for this user.
     */
    public function getOrCreateBudgetPreferences(): BudgetPreference
    {
        return $this->budgetPreferences()->firstOrCreate(['user_id' => $this->id]);
    }

    /**
     * Check if user has set up their monthly salary.
     */
    public function hasMonthlySalary(): bool
    {
        return $this->monthly_salary !== null && $this->monthly_salary > 0;
    }

    /**
     * Generate automatic budget templates based on salary and preferences.
     */
    public function generateAutomaticBudgetTemplates(): array
    {
        if (!$this->hasMonthlySalary()) {
            throw new \Exception('Monthly salary must be set before generating automatic budget templates.');
        }

        $preferences = $this->getOrCreateBudgetPreferences();
        return $preferences->generateBudgetTemplates((float) $this->monthly_salary);
    }
}
