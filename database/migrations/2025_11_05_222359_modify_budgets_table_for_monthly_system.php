<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            // Remove old fields that don't fit monthly system
            $table->dropColumn(['period', 'start_date', 'end_date']);
            
            // Add monthly system fields with default values for existing records
            $table->foreignId('budget_template_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('month')->default(11); // Default to November for existing records
            $table->integer('year')->default(2025); // Default to current year
            $table->string('category')->nullable();
        });
        
        // Add unique constraint after adding columns
        Schema::table('budgets', function (Blueprint $table) {
            $table->unique(['user_id', 'budget_template_id', 'month', 'year'], 'unique_monthly_budget');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            // Remove monthly system fields
            $table->dropForeign(['budget_template_id']);
            $table->dropUnique('unique_monthly_budget');
            $table->dropColumn(['budget_template_id', 'month', 'year', 'category']);
            
            // Restore old fields
            $table->enum('period', ['monthly', 'yearly']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
        });
    }
};
