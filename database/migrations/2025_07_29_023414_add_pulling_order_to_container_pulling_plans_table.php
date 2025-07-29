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
            $table->integer('pulling_order')->nullable()->after('pulling_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('container_pulling_plans', function (Blueprint $table) {
            $table->dropColumn('pulling_order');
        });
    }
};
