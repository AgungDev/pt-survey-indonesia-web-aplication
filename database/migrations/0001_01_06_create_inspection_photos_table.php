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
        Schema::create('inspection_photos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('inspection_id')->index();
            $table->uuid('finding_id')->nullable()->index();
            $table->string('photo_url');
            $table->string('photo_type');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('inspection_id')->references('id')->on('inspections')->cascadeOnDelete();
            $table->foreign('finding_id')->references('id')->on('inspection_findings')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspection_photos', function (Blueprint $table) {
            $table->dropForeign(['inspection_id']);
            $table->dropForeign(['finding_id']);
        });
        Schema::dropIfExists('inspection_photos');
    }
};
