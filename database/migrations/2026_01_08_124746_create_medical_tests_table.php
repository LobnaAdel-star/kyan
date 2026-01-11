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
        Schema::create('medical_tests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('visit_id')->constrained()->onDelete('cascade');
    
    $table->string('test_name'); // اسم التحليل (CBC, Glucose, etc.)
    $table->text('description')->nullable();
    $table->enum('status', ['pending', 'completed'])->default('pending'); // حالة التحليل
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_tests');
    }
};
