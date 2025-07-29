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
        Schema::create('container_pulling_plans', function (Blueprint $table) {
            $table->id();
            $table->string('pulling_plan_no')->unique();
            $table->foreignId('container_order_plan_id')->constrained()->onDelete('cascade');
            $table->date('pulling_date');
            $table->string('destination')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('1: Planned, 2: In Progress, 3: Completed');
            $table->foreignId('user_id')->constrained('users');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_pulling_plans');
    }
};
