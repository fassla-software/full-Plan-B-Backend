<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('department_id');
            $table->bigInteger('admin_id')->nullable();
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('freelancer_id')->nullable();
            $table->text('title')->nullable();
            $table->text('subject')->nullable();
            $table->string('priority')->nullable();
            $table->string('status')->default('open');
            $table->text('via')->nullable()->comment('admin, client, freelancer');
            $table->string('operating_system')->nullable();
            $table->string('user_agent')->nullable();
            $table->longText('description');
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
        Schema::dropIfExists('tickets');
    }
};
