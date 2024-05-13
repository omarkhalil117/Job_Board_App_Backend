<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('employers', function (Blueprint $table) {
            $table->string('logo', 512)->change();
            
        });
    }

    public function down()
    {
        Schema::table('employers', function (Blueprint $table) {
            $table->string('logo')->change();
        });
    }
};
