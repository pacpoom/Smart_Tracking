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
        Schema::table('container_tacking_photos', function (Blueprint $table) {
            $table->string('batch_key')->after('file_path')->nullable()->comment('To group photos uploaded at the same time');
            $table->text('remarks')->after('batch_key')->nullable()->comment('Remarks for this batch of photos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('container_tacking_photos', function (Blueprint $table) {
            $table->dropColumn(['batch_key', 'remarks']);
        });
    }
};
