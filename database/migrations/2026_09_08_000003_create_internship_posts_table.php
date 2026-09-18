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
        Schema::create('internship_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_profile_id')->constrained()->cascadeOnDelete();
            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->string('category')->default('General IT')->index();
            $table->longText('description');
            $table->text('responsibilities')->nullable();
            $table->text('requirements')->nullable();
            $table->string('location');
            $table->enum('type', ['remote', 'on_site', 'hybrid'])->default('on_site')->index();
            $table->unsignedSmallInteger('duration_weeks')->default(12);
            $table->decimal('stipend', 10, 2)->nullable();
            $table->boolean('is_stipend_disclosed')->default(true);
            $table->unsignedSmallInteger('slots')->default(1);
            $table->date('deadline')->index();
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'rejected', 'closed'])->default('pending_approval')->index();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_posts');
    }
};
