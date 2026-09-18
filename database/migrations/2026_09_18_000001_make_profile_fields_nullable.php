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
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('student_id_number')->nullable()->change();
            $table->string('department')->nullable()->change();
            $table->string('major')->nullable()->change();
            $table->unsignedSmallInteger('cohort_year')->nullable()->change();
        });

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->string('company_name')->nullable()->change();
            $table->string('industry')->nullable()->change();
            $table->string('location')->nullable()->change();
            $table->string('contact_person')->nullable()->change();
            $table->string('contact_phone')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('student_id_number')->nullable(false)->change();
            $table->string('department')->nullable(false)->change();
            $table->string('major')->nullable(false)->change();
            $table->unsignedSmallInteger('cohort_year')->nullable(false)->change();
        });

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->string('company_name')->nullable(false)->change();
            $table->string('industry')->nullable(false)->change();
            $table->string('location')->nullable(false)->change();
            $table->string('contact_person')->nullable(false)->change();
            $table->string('contact_phone')->nullable(false)->change();
        });
    }
};
