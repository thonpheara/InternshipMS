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
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('student_id_number')->nullable()->unique()->index();
            $table->string('department')->nullable();
            $table->string('major')->nullable();
            $table->unsignedSmallInteger('cohort_year')->nullable()->index();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->string('resume_path')->nullable();
            $table->string('phone')->nullable();
            $table->json('skills')->nullable();
            $table->text('bio')->nullable();
            $table->enum('eligibility_status', ['pending', 'eligible', 'ineligible'])->default('pending')->index();
            $table->text('eligibility_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
