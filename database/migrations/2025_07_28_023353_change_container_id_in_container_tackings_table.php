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
        Schema::table('container_tackings', function (Blueprint $table) {
            // Drop the old foreign key and column
            $table->dropForeign(['container_id']);
            $table->dropColumn('container_id');

            // Add the new foreign key and column
            $table->foreignId('container_order_plan_id')->after('id')->constrained('container_order_plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('container_tackings', function (Blueprint $table) {
            // Revert the changes
            $table->dropForeign(['container_order_plan_id']);
            $table->dropColumn('container_order_plan_id');

            $table->foreignId('container_id')->after('id')->constrained('containers');
        });
    }
};
