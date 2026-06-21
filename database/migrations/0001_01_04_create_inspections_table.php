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
        Schema::create('inspections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('survey_timestamp');
            $table->uuid('equipment_id')->index();
            $table->uuid('inspector_id')->index();
            $table->string('inspection_type');
            $table->text('inspection_result')->nullable();
            $table->text('recommendation')->nullable();
            $table->string('unit_photo')->nullable();
            $table->string('status')->default('Draft');
            $table->uuid('approved_by')->nullable()->index();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('equipment_id')->references('id')->on('equipments')->cascadeOnDelete();
            $table->foreign('inspector_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropForeign(['equipment_id']);
            $table->dropForeign(['inspector_id']);
            $table->dropForeign(['approved_by']);
        });
        Schema::dropIfExists('inspections');
    }
};
