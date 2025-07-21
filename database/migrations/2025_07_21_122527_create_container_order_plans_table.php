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
        Schema::create('container_order_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id')->constrained()->onDelete('cascade');
            $table->string('model')->nullable();
            $table->string('type')->nullable();
            $table->string('house_bl')->nullable();
            $table->date('eta_date')->nullable();
            $table->integer('free_time')->nullable();
            $table->date('checkin_date')->nullable();
            $table->boolean('is_active')->default(true)->comment('active_flg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_order_plans');
    }
};
