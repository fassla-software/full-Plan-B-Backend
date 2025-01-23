<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_attributes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('create_project_id');
            $table->string('type')->nullable();
            $table->string('check_numeric_title')->nullable();
            $table->string('basic_check_numeric')->nullable();
            $table->string('standard_check_numeric')->nullable();
            $table->string('premium_check_numeric')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_attributes');
    }
};
