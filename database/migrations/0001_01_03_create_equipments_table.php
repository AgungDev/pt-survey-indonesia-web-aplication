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
        Schema::create('equipments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('equipment_name');
            $table->string('equipment_category')->nullable();
            $table->string('location')->nullable();
            $table->string('unit_number')->unique();
            $table->string('serial_number')->unique();
            $table->string('model_type')->nullable();
            $table->string('brand')->nullable();
            $table->string('capacity')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('equipments', function (Blueprint $table) {
            $table->index(['equipment_category']);
            $table->index(['location']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
