<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_histories', function (Blueprint $table) {
            $table->string('file_path')->nullable()->after('filename');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('review_comment')->nullable()->after('approved_at');
            $table->timestamp('reviewed_at')->nullable()->after('review_comment');
        });
    }

    public function down(): void
    {
        Schema::table('import_histories', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'approved_by', 'approved_at', 'review_comment', 'reviewed_at']);
        });
    }
};
