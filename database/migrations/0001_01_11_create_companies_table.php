<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('industry_id')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->uuid('created_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('industry_id')->references('id')->on('industries')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['industry_id']);
            $table->dropForeign(['created_by']);
        });
        Schema::dropIfExists('companies');
    }
};
