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
        Schema::table('container_stocks', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default(1)->after('yard_location_id')->comment('1: Full, 2: Partial, 3: Empty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('container_stocks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
