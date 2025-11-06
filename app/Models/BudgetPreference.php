<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'no_rent',
        'no_car_payment',
        'no_insurance',
        'no_groceries',
        'no_phone_payment',
        'no_utilities',
        'no_internet',
        'no_gas',
        'no_maintenance',
        'no_subscriptions',
        'housing_percentage',
        'transportation_percentage',
        'food_percentage',
        'savings_percentage',
        'insurance_percentage',
        'debt_percentage',
        'personal_percentage',
        'utilities_percentage',
        'miscellaneous_percentage',
        'monthly_investment_amount',
        'auto_invest_enabled',
    ];

    protected $casts = [
        'no_rent' => 'boolean',
        'no_car_payment' => 'boolean',
        'no_insurance' => 'boolean',
        'no_groceries' => 'boolean',
        'no_phone_payment' => 'boolean',
        'no_utilities' => 'boolean',
        'no_internet' => 'boolean',
        'no_gas' => 'boolean',
        'no_maintenance' => 'boolean',
        'no_subscriptions' => 'boolean',
        'housing_percentage' => 'decimal:2',
        'transportation_percentage' => 'decimal:2',
        'food_percentage' => 'decimal:2',
        'savings_percentage' => 'decimal:2',
        'insurance_percentage' => 'decimal:2',
        'debt_percentage' => 'decimal:2',
        'personal_percentage' => 'decimal:2',
        'utilities_percentage' => 'decimal:2',
        'miscellaneous_percentage' => 'decimal:2',
        'monthly_investment_amount' => 'decimal:2',
        'auto_invest_enabled' => 'boolean',
    ];

    /**
     * Get the user that owns the budget preferences.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get standard budget category percentages based on the 50/30/20 rule
     * and detailed budget recommendations.
     */
    public function getStandardPercentages(): array
    {
        return [
            'housing' => 28.0,          // Rent/mortgage, property taxes, utilities
            'transportation' => 12.0,    // Car payment, gas, insurance, maintenance
            'food' => 12.0,             // Groceries and dining
            'savings' => 15.0,          // Emergency fund, retirement, investments
            'insurance' => 7.0,         // Health, life, disability
            'debt' => 8.0,              // Credit cards, loans (beyond minimum)
            'personal' => 8.0,          // Entertainment, hobbies, personal care
            'utilities' => 5.0,         // Phone, internet if not in housing
            'miscellaneous' => 5.0,     // Clothing, subscriptions, unexpected
        ];
    }

    /**
     * Get adjusted percentages based on user's exemptions and custom overrides.
     */
    public function getAdjustedPercentages(): array
    {
        $standard = $this->getStandardPercentages();
        $adjusted = [];
        $exemptedTotal = 0;

        // Check for custom overrides first
        foreach ($standard as $category => $percentage) {
            $customField = $category . '_percentage';
            if ($this->$customField !== null) {
                $adjusted[$category] = (float) $this->$customField;
                continue;
            }

            // Check if user is exempt from this category
            $isExempt = false;
            switch ($category) {
                case 'housing':
                    $isExempt = $this->no_rent;
                    break;
                case 'transportation':
                    $isExempt = $this->no_car_payment && $this->no_gas && $this->no_maintenance;
                    break;
                case 'food':
                    $isExempt = $this->no_groceries;
                    break;
                case 'insurance':
                    $isExempt = $this->no_insurance;
                    break;
                case 'utilities':
                    $isExempt = $this->no_phone_payment && $this->no_utilities && $this->no_internet;
                    break;
                case 'miscellaneous':
                    $isExempt = $this->no_subscriptions;
                    break;
            }

            if ($isExempt) {
                $adjusted[$category] = 0;
                $exemptedTotal += $percentage;
            } else {
                $adjusted[$category] = $percentage;
            }
        }

        // Redistribute exempted percentages to savings (smart financial practice)
        if ($exemptedTotal > 0) {
            $adjusted['savings'] += $exemptedTotal;
        }

        return $adjusted;
    }

    /**
     * Get budget category templates with calculated amounts based on monthly salary.
     */
    public function generateBudgetTemplates(float $monthlySalary): array
    {
        $percentages = $this->getAdjustedPercentages();
        $templates = [];

        // Generate regular budget templates based on FULL SALARY (investment is separate)
        foreach ($percentages as $category => $percentage) {
            if ($percentage > 0) {
                $amount = ($monthlySalary * $percentage) / 100;
                $templates[] = [
                    'name' => ucfirst($category),
                    'category' => $category,
                    'amount' => round($amount, 2),
                    'description' => $this->getCategoryDescription($category),
                ];
            }
        }

        return $templates;
    }

    /**
     * Get investment allocation details.
     */
    public function getInvestmentAllocation(): ?array
    {
        if (!$this->auto_invest_enabled || $this->monthly_investment_amount <= 0) {
            return null;
        }

        return [
            'name' => 'Investments',
            'category' => 'investments',
            'amount' => (float) $this->monthly_investment_amount,
            'description' => 'Automatic monthly investment allocation for long-term wealth building',
        ];
    }

    /**
     * Get description for each budget category.
     */
    private function getCategoryDescription(string $category): string
    {
        $descriptions = [
            'housing' => 'Rent/mortgage, property taxes, home insurance, utilities',
            'transportation' => 'Car payment, gas, car insurance, maintenance, public transport',
            'food' => 'Groceries, dining out, food delivery',
            'savings' => 'Emergency fund, retirement, investments, future goals',
            'insurance' => 'Health insurance, life insurance, disability insurance',
            'debt' => 'Credit card payments, loan payments (beyond minimum)',
            'personal' => 'Entertainment, hobbies, personal care, gym membership',
            'utilities' => 'Phone bill, internet, streaming services',
            'miscellaneous' => 'Clothing, unexpected expenses, miscellaneous purchases',
            'investments' => 'Automatic monthly investment allocation for long-term wealth building',
        ];

        return $descriptions[$category] ?? 'Budget category';
    }
}
