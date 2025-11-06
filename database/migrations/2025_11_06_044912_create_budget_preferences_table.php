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
        Schema::create('budget_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Expense exemptions - checkboxes for what user doesn't pay for
            $table->boolean('no_rent')->default(false);
            $table->boolean('no_car_payment')->default(false);
            $table->boolean('no_insurance')->default(false);
            $table->boolean('no_groceries')->default(false);
            $table->boolean('no_phone_payment')->default(false);
            $table->boolean('no_utilities')->default(false);
            $table->boolean('no_internet')->default(false);
            $table->boolean('no_gas')->default(false);
            $table->boolean('no_maintenance')->default(false);
            $table->boolean('no_subscriptions')->default(false);
            
            // Custom percentage overrides (optional - if user wants to customize beyond standard)
            $table->decimal('housing_percentage', 5, 2)->nullable(); // 25-30% standard
            $table->decimal('transportation_percentage', 5, 2)->nullable(); // 10-15% standard
            $table->decimal('food_percentage', 5, 2)->nullable(); // 10-15% standard
            $table->decimal('savings_percentage', 5, 2)->nullable(); // 10-20% standard
            $table->decimal('insurance_percentage', 5, 2)->nullable(); // 5-10% standard
            $table->decimal('debt_percentage', 5, 2)->nullable(); // 5-10% standard
            $table->decimal('personal_percentage', 5, 2)->nullable(); // 5-10% standard
            $table->decimal('utilities_percentage', 5, 2)->nullable(); // 5-10% standard
            $table->decimal('miscellaneous_percentage', 5, 2)->nullable(); // 5-10% standard
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_preferences');
    }
};
