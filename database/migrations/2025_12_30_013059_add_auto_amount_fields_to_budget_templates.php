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
        Schema::table('budget_templates', function (Blueprint $table) {
            $table->boolean('is_auto_amount')->default(false)->after('is_automatic');
            $table->decimal('percentage', 5, 2)->nullable()->after('is_auto_amount')->comment('Percentage of salary/budget pool');
            $table->string('default_category', 100)->nullable()->after('category')->comment('Default budget category for auto-calculation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budget_templates', function (Blueprint $table) {
            $table->dropColumn(['is_auto_amount', 'percentage', 'default_category']);
        });
    }
};
