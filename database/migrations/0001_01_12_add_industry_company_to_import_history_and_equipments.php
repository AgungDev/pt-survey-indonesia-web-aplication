<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('import_histories', 'industry_id')) {
                $table->uuid('industry_id')->nullable()->after('approved_by')->index();
                $table->foreign('industry_id')->references('id')->on('industries')->nullOnDelete();
            }

            if (!Schema::hasColumn('import_histories', 'company_id')) {
                $table->uuid('company_id')->nullable()->after('industry_id')->index();
                $table->foreign('company_id')->references('id')->on('companies')->nullOnDelete();
            }
        });

        Schema::table('equipments', function (Blueprint $table) {
            if (!Schema::hasColumn('equipments', 'industry_id')) {
                $table->uuid('industry_id')->nullable()->after('id')->index();
                $table->foreign('industry_id')->references('id')->on('industries')->nullOnDelete();
            }

            if (!Schema::hasColumn('equipments', 'company_id')) {
                $table->uuid('company_id')->nullable()->after('industry_id')->index();
                $table->foreign('company_id')->references('id')->on('companies')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('import_histories', function (Blueprint $table) {
            if (Schema::hasColumn('import_histories', 'company_id')) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            }

            if (Schema::hasColumn('import_histories', 'industry_id')) {
                $table->dropForeign(['industry_id']);
                $table->dropColumn('industry_id');
            }
        });

        Schema::table('equipments', function (Blueprint $table) {
            if (Schema::hasColumn('equipments', 'company_id')) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            }

            if (Schema::hasColumn('equipments', 'industry_id')) {
                $table->dropForeign(['industry_id']);
                $table->dropColumn('industry_id');
            }
        });
    }
};
