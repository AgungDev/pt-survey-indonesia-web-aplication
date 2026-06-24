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
        if (Schema::hasTable('import_histories')) {
            Schema::table('import_histories', function (Blueprint $table) {
                if (!Schema::hasColumn('import_histories', 'file_path')) {
                    $table->string('file_path')->nullable()->after('filename');
                }
                if (!Schema::hasColumn('import_histories', 'approved_by')) {
                    $table->uuid('approved_by')->nullable()->after('status')->index();
                }
                if (!Schema::hasColumn('import_histories', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('approved_by');
                }
                if (!Schema::hasColumn('import_histories', 'review_comment')) {
                    $table->text('review_comment')->nullable()->after('approved_at');
                }
                if (!Schema::hasColumn('import_histories', 'reviewed_at')) {
                    $table->timestamp('reviewed_at')->nullable()->after('review_comment');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('import_histories')) {
            Schema::table('import_histories', function (Blueprint $table) {
                if (Schema::hasColumn('import_histories', 'reviewed_at')) {
                    $table->dropColumn('reviewed_at');
                }
                if (Schema::hasColumn('import_histories', 'review_comment')) {
                    $table->dropColumn('review_comment');
                }
                if (Schema::hasColumn('import_histories', 'approved_at')) {
                    $table->dropColumn('approved_at');
                }
                if (Schema::hasColumn('import_histories', 'approved_by')) {
                    $table->dropColumn('approved_by');
                }
                if (Schema::hasColumn('import_histories', 'file_path')) {
                    $table->dropColumn('file_path');
                }
            });
        }
    }
};
