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
        Schema::create('inspection_findings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('inspection_id')->index();
            $table->integer('finding_number');
            $table->text('finding_description');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('inspection_id')->references('id')->on('inspections')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspection_findings', function (Blueprint $table) {
            $table->dropForeign(['inspection_id']);
        });
        Schema::dropIfExists('inspection_findings');
    }
};
