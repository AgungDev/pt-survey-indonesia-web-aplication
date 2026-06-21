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
        Schema::create('import_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('filename');
            $table->integer('total_rows')->default(0);
            $table->integer('success_rows')->default(0);
            $table->integer('failed_rows')->default(0);
            $table->string('status')->default('Pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->uuid('created_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('import_histories', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });
        Schema::dropIfExists('import_histories');
    }
};
