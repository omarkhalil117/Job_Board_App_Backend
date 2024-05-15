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
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('resume')->nullable()->change();
            $table->string('education')->nullable()->change();
            $table->string('faculty')->nullable()->change();
            $table->string('city')->nullable()->change(); 
            $table->string('experience_level')->enum('experience_level', ['junior','mid-senior','senior','manager','team-lead'])->nullable()->change();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('resume')->nullable(false)->change();
            $table->string('education')->nullable(false)->change();
            $table->string('faculty')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('experience_level')->enum('experience_level', ['junior','mid-senior','senior','manager','team-lead'])->nullable(false)->change();
        });
    }
};
