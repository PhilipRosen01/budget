<?php

namespace App\Http\Controllers;

use App\Models\BudgetPreference;
use App\Models\BudgetTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OnboardingController extends Controller
{
    /**
     * Show the onboarding welcome screen
     */
    public function index()
    {
        $user = Auth::user();
        
        // If user has already completed onboarding, redirect to dashboard
        if ($user->onboarding_completed) {
            return redirect()->route('dashboard');
        }
        
        return view('onboarding.welcome');
    }

    /**
     * Show step 1: Monthly Salary Setup
     */
    public function step1()
    {
        $user = Auth::user();
        
        if ($user->onboarding_completed) {
            return redirect()->route('dashboard');
        }
        
        return view('onboarding.step1', [
            'currentSalary' => $user->monthly_salary ?? 0
        ]);
    }

    /**
     * Process step 1: Save monthly salary
     */
    public function processStep1(Request $request)
    {
        $validated = $request->validate([
            'monthly_salary' => 'required|numeric|min:0|max:999999.99'
        ]);

        $user = Auth::user();
        $user->update([
            'monthly_salary' => $validated['monthly_salary']
        ]);

        return redirect()->route('onboarding.step2');
    }

    /**
     * Show step 2: Investment Amount Setup
     */
    public function step2()
    {
        $user = Auth::user();
        
        if ($user->onboarding_completed) {
            return redirect()->route('dashboard');
        }

        // Ensure user has a salary set
        if (!$user->monthly_salary) {
            return redirect()->route('onboarding.step1')->with('error', 'Please set your monthly salary first.');
        }

        $preferences = $user->budgetPreferences ?? new BudgetPreference();
        
        return view('onboarding.step2', [
            'salary' => $user->monthly_salary,
            'currentInvestment' => $preferences->monthly_investment_amount ?? 0,
            'autoInvestEnabled' => $preferences->auto_invest_enabled ?? false
        ]);
    }

    /**
     * Process step 2: Save investment preferences
     */
    public function processStep2(Request $request)
    {
        $validated = $request->validate([
            'monthly_investment_amount' => 'required|numeric|min:0',
            'auto_invest_enabled' => 'boolean'
        ]);

        $user = Auth::user();
        
        // Validate investment amount doesn't exceed salary
        if ($validated['monthly_investment_amount'] > $user->monthly_salary) {
            return back()->withErrors([
                'monthly_investment_amount' => 'Investment amount cannot exceed your monthly salary.'
            ])->withInput();
        }

        // Create or update budget preferences
        $user->budgetPreferences()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'monthly_investment_amount' => $validated['monthly_investment_amount'],
                'auto_invest_enabled' => $validated['auto_invest_enabled'] ?? true,
            ]
        );

        return redirect()->route('onboarding.step3');
    }

    /**
     * Show step 3: Budget Categories Selection
     */
    public function step3()
    {
        $user = Auth::user();
        
        if ($user->onboarding_completed) {
            return redirect()->route('dashboard');
        }

        // Ensure user has completed previous steps
        if (!$user->monthly_salary) {
            return redirect()->route('onboarding.step1')->with('error', 'Please complete the salary setup first.');
        }

        $preferences = $user->budgetPreferences;
        if (!$preferences) {
            return redirect()->route('onboarding.step2')->with('error', 'Please complete the investment setup first.');
        }

        $availableBudget = $user->monthly_salary - $preferences->monthly_investment_amount;

        return view('onboarding.step3', [
            'salary' => $user->monthly_salary,
            'investment' => $preferences->monthly_investment_amount,
            'availableBudget' => $availableBudget,
            'preferences' => $preferences
        ]);
    }

    /**
     * Process step 3: Save selected categories and complete onboarding
     */
    public function processStep3(Request $request)
    {
        $validated = $request->validate([
            'selected_categories' => 'required|json',
        ]);

        $user = Auth::user();
        $preferences = $user->budgetPreferences;

        if (!$preferences) {
            return redirect()->route('onboarding.step2')->with('error', 'Please complete the investment setup first.');
        }

        // Decode selected categories
        $selectedCategories = json_decode($validated['selected_categories'], true);
        
        if (empty($selectedCategories)) {
            return back()->withErrors([
                'selected_categories' => 'Please select at least one budget category.'
            ])->withInput();
        }

        // Generate automatic budget templates based on selected categories
        try {
            $this->generateCategoryTemplates($user, $selectedCategories);
            Log::info('Successfully generated ' . count($selectedCategories) . ' category templates for user ' . $user->id);
        } catch (\Exception $e) {
            Log::error('Failed to generate category templates for user ' . $user->id . ': ' . $e->getMessage());
            // Don't fail onboarding if template generation fails
        }

        // Mark onboarding as completed with error handling
        $user = Auth::user();
        
        try {
            // First try with mass assignment
            $result = $user->update([
                'onboarding_completed' => true,
                'onboarding_completed_at' => Carbon::now()
            ]);
            
            if (!$result) {
                throw new \Exception('Update returned false');
            }
            
            // Refresh the user model to ensure we have the latest data
            $user->refresh();
            
            Log::info('Onboarding completion successful for user ' . $user->id);
            
        } catch (\Exception $e) {
            // Log the error and try alternative approach
            Log::error('Failed to update onboarding completion: ' . $e->getMessage());
            
            try {
                // Direct property assignment
                $user->onboarding_completed = 1; // Use integer instead of boolean
                $user->onboarding_completed_at = Carbon::now();
                $saved = $user->save();
                
                if (!$saved) {
                    throw new \Exception('Save returned false');
                }
                
                $user->refresh();
                Log::info('Alternative onboarding completion successful for user ' . $user->id);
                
            } catch (\Exception $e2) {
                // If still failing, try direct database update
                Log::error('Alternative onboarding completion update also failed: ' . $e2->getMessage());
                
                try {
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update([
                            'onboarding_completed' => 1,
                            'onboarding_completed_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                    
                    $user->refresh();
                    Log::info('Direct DB onboarding completion successful for user ' . $user->id);
                    
                } catch (\Exception $e3) {
                    Log::error('All onboarding completion methods failed: ' . $e3->getMessage());
                }
            }
        }

        return redirect()->route('onboarding.complete');
    }

    /**
     * Show onboarding completion screen
     */
    public function complete()
    {
        $user = Auth::user();
        
        // Refresh user to get latest data
        $user->refresh();
        
        // Log for debugging
        Log::info('Complete method called for user ' . $user->id . ', onboarding_completed: ' . ($user->onboarding_completed ? 'true' : 'false'));
        
        // If user somehow doesn't have onboarding completed, try to set it
        if (!$user->onboarding_completed) {
            Log::warning('User reached completion screen but onboarding not marked complete, setting it now');
            
            try {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'onboarding_completed' => 1,
                        'onboarding_completed_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                
                $user->refresh();
            } catch (\Exception $e) {
                Log::error('Failed to set onboarding complete in complete method: ' . $e->getMessage());
            }
        }

        return view('onboarding.complete', [
            'user' => $user
        ]);
    }

    /**
     * Redirect to dashboard after completion
     */
    public function toDashboard()
    {
        return redirect()->route('dashboard');
    }

    /**
     * Generate category-based budget templates for the user during onboarding
     */
    private function generateCategoryTemplates($user, $selectedCategories)
    {
        if (!$user->hasMonthlySalary()) {
            throw new \Exception('User does not have monthly salary set');
        }

        $budgetCategories = config('budget_categories');
        $preferences = $user->budgetPreferences;
        $availableBudget = $user->monthly_salary - ($preferences->monthly_investment_amount ?? 0);

        DB::beginTransaction();

        try {
            // Delete existing automatic templates (ones that were auto-generated during onboarding)
            $user->budgetTemplates()
                ->where('is_automatic', true)
                ->delete();

            $createdTemplates = [];

            // Create templates for each selected category
            foreach ($selectedCategories as $categoryName) {
                // Find the category config
                $categoryConfig = collect($budgetCategories)->firstWhere('name', $categoryName);
                
                if (!$categoryConfig) {
                    Log::warning("Category config not found for: {$categoryName}");
                    continue;
                }

                // Calculate the amount based on percentage
                $percentage = $categoryConfig['default_percentage'];
                $amount = ($availableBudget * $percentage) / 100;

                // Create the template with auto-amount enabled
                $template = $user->budgetTemplates()->create([
                    'name' => $categoryConfig['name'],
                    'category' => strtolower(str_replace(' ', '_', $categoryConfig['name'])), // Convert to snake_case
                    'amount' => round($amount, 2),
                    'description' => $categoryConfig['description'],
                    'is_active' => true,
                    'is_automatic' => true, // Flag to identify auto-generated templates
                    'is_auto_amount' => true, // Enable auto-calculation
                    'percentage' => $percentage,
                    'default_category' => $categoryConfig['name'],
                ]);
                
                // Create budget for current month
                $currentMonth = Carbon::now();
                $template->createMonthlyBudget($currentMonth->month, $currentMonth->year);
                
                $createdTemplates[] = $template;
            }

            // Create investment template separately if enabled
            $investmentAllocation = $preferences->getInvestmentAllocation();
            if ($investmentAllocation) {
                $investmentTemplate = $user->budgetTemplates()->create([
                    'name' => $investmentAllocation['name'],
                    'category' => $investmentAllocation['category'],
                    'amount' => $investmentAllocation['amount'],
                    'description' => $investmentAllocation['description'],
                    'is_active' => true,
                    'is_automatic' => true,
                    'is_auto_amount' => false, // Fixed amount for investments
                ]);
                
                // Create budget for current month
                $currentMonth = Carbon::now();
                $investmentTemplate->createMonthlyBudget($currentMonth->month, $currentMonth->year);
                
                $createdTemplates[] = $investmentTemplate;
            }

            DB::commit();

            Log::info('Generated ' . count($createdTemplates) . ' category templates for user ' . $user->id);
            return $createdTemplates;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Generate automatic budget templates for the user during onboarding (OLD METHOD - kept for backward compatibility)
     */
    private function generateAutomaticTemplates($user)
    {
        if (!$user->hasMonthlySalary()) {
            throw new \Exception('User does not have monthly salary set');
        }

        DB::beginTransaction();

        try {
            // Generate template data using the User model method
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
                
                // Create budget for current month
                $currentMonth = Carbon::now();
                $createdTemplate->createMonthlyBudget($currentMonth->month, $currentMonth->year);
                
                $createdTemplates[] = $createdTemplate;
            }

            // Create investment template separately if enabled
            $preferences = $user->getOrCreateBudgetPreferences();
            $investmentAllocation = $preferences->getInvestmentAllocation();
            if ($investmentAllocation) {
                $investmentTemplate = $user->budgetTemplates()->create([
                    'name' => $investmentAllocation['name'],
                    'category' => $investmentAllocation['category'],
                    'amount' => $investmentAllocation['amount'],
                    'description' => $investmentAllocation['description'],
                    'is_active' => true,
                    'is_automatic' => true,
                ]);
                
                // Create budget for current month
                $currentMonth = Carbon::now();
                $investmentTemplate->createMonthlyBudget($currentMonth->month, $currentMonth->year);
                
                $createdTemplates[] = $investmentTemplate;
            }

            DB::commit();

            Log::info('Generated ' . count($createdTemplates) . ' automatic templates for user ' . $user->id);
            return $createdTemplates;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}