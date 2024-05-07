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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employer_id');
            $table->foreign('employer_id')->references('id')->on('employers')->onDelete('cascade');
            $table->string('job_title');
            $table->text('description');
            $table->text('responsibilities');
            $table->text('qualifications');
            $table->integer('start_salary');
            $table->integer('end_salary');
            $table->string('location');
            $table->enum('work_type', ['remote','on-site','hybrid']);
            $table->date('application_deadline');
            $table->enum('status', ['pending','approved','rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
