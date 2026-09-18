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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('placement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evaluator_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['midterm', 'final', 'supervisor_visit'])->index();
            $table->unsignedTinyInteger('performance_rating'); // 1-5
            $table->unsignedTinyInteger('technical_skills_rating')->nullable();
            $table->unsignedTinyInteger('soft_skills_rating')->nullable();
            $table->unsignedTinyInteger('attendance_punctuality_rating')->nullable();
            $table->text('comments');
            $table->enum('recommendation', ['outstanding', 'satisfactory', 'needs_improvement', 'unsatisfactory'])->default('satisfactory');
            $table->timestamp('submitted_at')->useCurrent()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
