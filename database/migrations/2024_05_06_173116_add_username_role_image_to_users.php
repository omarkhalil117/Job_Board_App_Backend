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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique();
            $table->enum('role', ['admin','employer','candidate']);
            $table->string('imgae');
            $table->unsignedBigInteger('userable_id')->nullable();
            $table->string('userable_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $this->dropColumn('username');
            $this->dropColumn('role');
            $this->dropColumn('image');
            $this->dropColumn('userable_id');
            $this->dropColumn('userable_type');
        });
    }
};
