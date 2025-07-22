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
        Schema::create('container_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_order_plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('yard_location_id')->constrained('yard_locations');
            $table->date('checkin_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_stocks');
    }
};
