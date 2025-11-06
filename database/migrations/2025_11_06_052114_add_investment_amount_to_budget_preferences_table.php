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
        Schema::table('budget_preferences', function (Blueprint $table) {
            $table->decimal('monthly_investment_amount', 10, 2)->default(1000.00)->after('miscellaneous_percentage');
            $table->boolean('auto_invest_enabled')->default(true)->after('monthly_investment_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budget_preferences', function (Blueprint $table) {
            $table->dropColumn(['monthly_investment_amount', 'auto_invest_enabled']);
        });
    }
};
