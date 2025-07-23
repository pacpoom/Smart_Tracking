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
        Schema::create('container_tacking_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_tacking_id')->constrained('container_tackings')->onDelete('cascade');
            $table->string('photo_type'); // e.g., document_1, front_truck, seal_photo
            $table->string('file_path', 2048);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_tacking_photos');
    }
};
