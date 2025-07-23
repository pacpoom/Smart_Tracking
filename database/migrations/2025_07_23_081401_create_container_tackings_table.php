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
        Schema::create('container_tackings', function (Blueprint $table) {
            $table->id();
            $table->string('job_type'); // Inbound, Outbound
            $table->string('container_type'); // CKD, AIR, LOCAL, EXPORT
            $table->string('transport_type'); // 4W, 6W, 10W, 20, 40, 40HQ
            $table->foreignId('container_id')->constrained('containers');
            $table->string('shipment')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_tackings');
    }
};
