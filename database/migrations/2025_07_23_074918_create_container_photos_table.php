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
        Schema::create('container_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_transaction_id')->constrained('container_transactions')->onDelete('cascade');
            $table->string('file_path', 2048);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_photos');
    }
};
