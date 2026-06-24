<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_approvals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('inspection_id')->index();
            $table->string('action'); // SUBMITTED | APPROVED | REJECTED | REVISED
            $table->text('comment')->nullable();
            $table->uuid('performed_by')->nullable();
            $table->timestamps();

            $table->foreign('inspection_id')->references('id')->on('inspections')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_approvals');
    }
};
