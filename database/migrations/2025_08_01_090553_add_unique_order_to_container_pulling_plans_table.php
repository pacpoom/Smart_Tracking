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
        Schema::table('container_pulling_plans', function (Blueprint $table) {
            // This ensures that for any given date, the pulling_order is unique.
            $table->unique(['pulling_date', 'pulling_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('container_pulling_plans', function (Blueprint $table) {
            $table->dropUnique(['pulling_date', 'pulling_order']);
        });
    }
};
