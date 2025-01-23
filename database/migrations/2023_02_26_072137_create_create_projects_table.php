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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('title');
            $table->longText('description');
            $table->string('image');
            $table->string('basic_title');
            $table->string('standard_title')->nullable();
            $table->string('premium_title')->nullable();
            $table->string('basic_revision')->nullable();
            $table->string('standard_revision')->nullable();
            $table->string('premium_revision')->nullable();
            $table->string('basic_delivery')->nullable();
            $table->string('standard_delivery')->nullable();
            $table->string('premium_delivery')->nullable();
            $table->double('basic_regular_charge');
            $table->double('basic_discount_charge')->nullable();
            $table->double('standard_regular_charge')->nullable();
            $table->double('standard_discount_charge')->nullable();
            $table->double('premium_regular_charge')->nullable();
            $table->double('premium_discount_charge')->nullable();
            $table->tinyInteger('project_on_off')->default(1)->comment('0=off, 1=on');
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
        Schema::dropIfExists('projects');
    }
};
