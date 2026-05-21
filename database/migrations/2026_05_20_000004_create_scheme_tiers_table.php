<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scheme_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_id')->constrained('scholarship_schemes')->onDelete('cascade');
            $table->string('tier_name');
            $table->text('criteria');
            $table->decimal('amount', 10, 2);
            $table->unsignedInteger('total_seats');
            $table->unsignedInteger('filled_seats')->default(0);
            $table->date('deadline');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheme_tiers');
    }
};
