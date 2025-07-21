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
        Schema::table('part_requests', function (Blueprint $table) {
            $table->date('delivery_date')->nullable()->after('status');
            $table->date('arrival_date')->nullable()->after('delivery_date');
            $table->string('delivery_document_path')->nullable()->after('arrival_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('part_requests', function (Blueprint $table) {
            $table->dropColumn(['delivery_date', 'arrival_date', 'delivery_document_path']);
        });
    }
};
