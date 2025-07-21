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
        Schema::create('yard_locations', function (Blueprint $table) {
            $table->id();
            $table->string('location_code')->unique();
            
            // Foreign keys to the new yard_categories table
            $table->foreignId('location_type_id')->nullable()->constrained('yard_categories');
            $table->foreignId('zone_id')->nullable()->constrained('yard_categories');
            $table->foreignId('area_id')->nullable()->constrained('yard_categories');
            $table->foreignId('bin_id')->nullable()->constrained('yard_categories');

            $table->boolean('is_active')->default(true)->comment('active_flg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yard_locations');
    }
};
