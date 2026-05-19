<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('enrollment_number')->unique();
            $table->string('home_state');
            $table->string('studying_state');
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->string('course');
            $table->string('year');
            $table->string('phone');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('students');
    }
};