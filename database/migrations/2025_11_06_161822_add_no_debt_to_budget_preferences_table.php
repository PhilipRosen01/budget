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
            $table->boolean('no_debt')->default(false)->after('no_subscriptions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budget_preferences', function (Blueprint $table) {
            $table->dropColumn('no_debt');
        });
    }
};
