<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixOnboardingColumns extends Command
{
    protected $signature = 'fix:onboarding-columns';
    protected $description = 'Add onboarding columns to users table';

    public function handle()
    {
        try {
            // Check if columns exist
            $hasOnboardingCompleted = Schema::hasColumn('users', 'onboarding_completed');
            $hasOnboardingCompletedAt = Schema::hasColumn('users', 'onboarding_completed_at');

            if (!$hasOnboardingCompleted) {
                DB::statement('ALTER TABLE users ADD COLUMN onboarding_completed INTEGER DEFAULT 0');
                $this->info('Added onboarding_completed column');
            } else {
                $this->info('onboarding_completed column already exists');
            }

            if (!$hasOnboardingCompletedAt) {
                DB::statement('ALTER TABLE users ADD COLUMN onboarding_completed_at DATETIME NULL');
                $this->info('Added onboarding_completed_at column');
            } else {
                $this->info('onboarding_completed_at column already exists');
            }

            // Update existing users
            DB::statement("UPDATE users SET onboarding_completed = 1, onboarding_completed_at = datetime('now') WHERE onboarding_completed IS NULL OR onboarding_completed = 0");
            $this->info('Updated existing users to mark onboarding as completed');

            $this->info('Onboarding columns setup completed successfully!');
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}