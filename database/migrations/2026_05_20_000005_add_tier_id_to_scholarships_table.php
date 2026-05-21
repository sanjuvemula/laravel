<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('scholarships', 'tier_id')) {
            Schema::table('scholarships', function (Blueprint $table) {
                $table->foreignId('tier_id')
                    ->nullable()
                    ->after('student_id')
                    ->constrained('scheme_tiers')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('scholarships', 'document_path')) {
            Schema::table('scholarships', function (Blueprint $table) {
                $table->string('document_path')->nullable()->after('remarks');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('scholarships', 'tier_id')) {
            Schema::table('scholarships', function (Blueprint $table) {
                $table->dropConstrainedForeignId('tier_id');
            });
        }
    }
};
