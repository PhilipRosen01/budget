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
        Schema::create('purchase_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "New MacBook Pro", "Vacation to Japan"
            $table->text('description')->nullable();
            $table->decimal('target_amount', 10, 2); // How much they want to save
            $table->decimal('current_amount', 10, 2)->default(0); // How much they've saved so far
            $table->string('image_url')->nullable(); // Optional image of the goal
            $table->date('target_date')->nullable(); // When they want to achieve this
            $table->integer('priority')->default(1); // 1 = highest priority for allocation
            $table->boolean('is_active')->default(true);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_goals');
    }
};
