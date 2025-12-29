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
        // Add icon to budget_templates table
        Schema::table('budget_templates', function (Blueprint $table) {
            $table->string('icon', 10)->nullable()->after('category');
        });

        // Add icon to budgets table
        Schema::table('budgets', function (Blueprint $table) {
            $table->string('icon', 10)->nullable()->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budget_templates', function (Blueprint $table) {
            $table->dropColumn('icon');
        });

        Schema::table('budgets', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
