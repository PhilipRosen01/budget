<?php

namespace App\Console\Commands;

use App\Models\BudgetTemplate;
use App\Models\Budget;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMonthlyBudgets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budgets:generate-monthly {--month=} {--year=} {--user=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly budgets from templates for all users or a specific user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $month = $this->option('month') ?: Carbon::now()->month;
        $year = $this->option('year') ?: Carbon::now()->year;
        $userId = $this->option('user');

        $this->info("Generating budgets for " . Carbon::create($year, $month, 1)->format('F Y'));

        $users = $userId ? User::where('id', $userId)->get() : User::all();
        $generatedCount = 0;
        $skippedCount = 0;

        foreach ($users as $user) {
            $templates = $user->activeBudgetTemplates;
            
            foreach ($templates as $template) {
                // Check if budget already exists for this month/year
                $existingBudget = Budget::where('user_id', $user->id)
                    ->where('budget_template_id', $template->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->first();

                if ($existingBudget) {
                    $skippedCount++;
                    continue;
                }

                // Create the monthly budget
                $budget = $template->createMonthlyBudget($month, $year);
                $generatedCount++;
                
                $this->line("Created budget '{$budget->name}' for {$user->name}");
            }
        }

        $this->info("Budget generation complete!");
        $this->info("Generated: {$generatedCount} budgets");
        $this->info("Skipped (already exist): {$skippedCount} budgets");

        return Command::SUCCESS;
    }
}
