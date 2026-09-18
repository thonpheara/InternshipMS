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
        Schema::create('weekly_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('placement_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('week_number')->index();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('hours_completed', 5, 2);
            $table->text('tasks_summary');
            $table->text('learnings_challenges')->nullable();
            $table->enum('status', ['submitted', 'approved', 'revision_requested', 'rejected'])->default('submitted')->index();
            $table->text('company_feedback')->nullable();
            $table->text('supervisor_feedback')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['placement_id', 'week_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_logs');
    }
};
