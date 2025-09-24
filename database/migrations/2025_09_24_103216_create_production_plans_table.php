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
        Schema::create('production_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_no')->unique();
            $table->foreignId('vc_master_id')->constrained('vc_master');
            $table->integer('production_order');
            $table->date('production_date');
            $table->json('details');
            $table->foreignId('user_id')->constrained('users');
            $table->string('status')->default('planned'); // e.g., planned, in_progress, completed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_plans');
    }
};

