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
        Schema::create('prescription_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
    
    $table->string('medicine_name'); // اسم الدواء
    $table->string('dosage');        // الجرعة (مثلاً: 3 مرات يومياً)
    $table->string('duration');      // المدة (مثلاً: لمدة 5 أيام)
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
