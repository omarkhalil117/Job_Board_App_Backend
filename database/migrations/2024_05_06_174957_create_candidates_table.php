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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('resume');
            $table->string('education');
            $table->string('faculty');
            $table->string('city');
            $table->enum('experience_level', ['junior','mid-senior','senior','manager','team-lead']);
            $table->string('address');
            $table->string('linkedin');
            $table->string('github');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
