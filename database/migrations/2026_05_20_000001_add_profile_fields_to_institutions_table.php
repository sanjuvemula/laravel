<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->string('institution_type')->nullable()->after('registration_number');
            $table->string('affiliated_university')->nullable()->after('institution_type');
            $table->string('contact_email')->nullable()->after('affiliated_university');
            $table->string('contact_phone')->nullable()->after('contact_email');
        });
    }

    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn([
                'institution_type',
                'affiliated_university',
                'contact_email',
                'contact_phone',
            ]);
        });
    }
};
