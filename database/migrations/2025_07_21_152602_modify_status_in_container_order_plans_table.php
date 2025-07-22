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
        Schema::table('container_order_plans', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->unsignedTinyInteger('status')->default(1)->after('checkin_date')->comment('1: Pending, 2: Received, 3: Shipped Out');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('container_order_plans', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->boolean('is_active')->default(true)->comment('active_flg');
        });
    }
};
