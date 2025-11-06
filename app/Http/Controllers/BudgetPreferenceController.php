<?php

namespace App\Http\Controllers;

use App\Models\BudgetPreference;
use App\Models\BudgetTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetPreferenceController extends Controller
{
    /**
     * Update the user's budget preferences.
     */
    public function update(Request $request)
    {
        $request->validate([
            'monthly_salary' => 'nullable|numeric|min:0|max:999999.99',
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
            'housing_percentage' => 'nullable|numeric|min:0|max:100',
            'transportation_percentage' => 'nullable|numeric|min:0|max:100',
            'food_percentage' => 'nullable|numeric|min:0|max:100',
            'savings_percentage' => 'nullable|numeric|min:0|max:100',
            'insurance_percentage' => 'nullable|numeric|min:0|max:100',
            'debt_percentage' => 'nullable|numeric|min:0|max:100',
            'personal_percentage' => 'nullable|numeric|min:0|max:100',
            'utilities_percentage' => 'nullable|numeric|min:0|max:100',
            'miscellaneous_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $user = Auth::user();

        // Update monthly salary
        if ($request->has('monthly_salary')) {
            $user->update(['monthly_salary' => $request->monthly_salary]);
        }

        // Update or create budget preferences
        $preferences = $user->getOrCreateBudgetPreferences();
        
        $preferencesData = $request->only([
            'no_rent', 'no_car_payment', 'no_insurance', 'no_groceries',
            'no_phone_payment', 'no_utilities', 'no_internet', 'no_gas',
            'no_maintenance', 'no_subscriptions', 'housing_percentage',
            'transportation_percentage', 'food_percentage', 'savings_percentage',
            'insurance_percentage', 'debt_percentage', 'personal_percentage',
            'utilities_percentage', 'miscellaneous_percentage'
        ]);

        // Convert null strings to actual null values for percentage fields
        foreach ($preferencesData as $key => $value) {
            if (str_ends_with($key, '_percentage') && $value === '') {
                $preferencesData[$key] = null;
            }
        }

        $preferences->update($preferencesData);

        return redirect()->back()->with('success', 'Budget preferences updated successfully!');
    }

    /**
     * Generate automatic budget templates based on salary and preferences.
     */
    public function generateAutomaticTemplates(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasMonthlySalary()) {
            return redirect()->back()->with('error', 'Please set your monthly salary before generating automatic templates.');
        }

        try {
            DB::beginTransaction();

            // Generate template data
            $templateData = $user->generateAutomaticBudgetTemplates();

            // Delete existing automatic templates (ones that were auto-generated)
            $user->budgetTemplates()
                ->where('is_automatic', true)
                ->delete();

            // Create new automatic templates
            $createdTemplates = [];
            foreach ($templateData as $template) {
                $createdTemplate = $user->budgetTemplates()->create([
                    'name' => $template['name'],
                    'category' => $template['category'],
                    'amount' => $template['amount'],
                    'description' => $template['description'],
                    'is_active' => true,
                    'is_automatic' => true, // Flag to identify auto-generated templates
                ]);
                $createdTemplates[] = $createdTemplate;
            }

            DB::commit();

            $count = count($createdTemplates);
            return redirect()->route('budget-templates.index')
                ->with('success', "Successfully generated {$count} automatic budget templates based on your salary and preferences!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to generate automatic templates: ' . $e->getMessage());
        }
    }

    /**
     * Preview what the automatic templates would look like without creating them.
     */
    public function previewTemplates(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasMonthlySalary()) {
            return response()->json(['error' => 'Monthly salary not set'], 400);
        }

        try {
            $templateData = $user->generateAutomaticBudgetTemplates();
            return response()->json(['templates' => $templateData]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
