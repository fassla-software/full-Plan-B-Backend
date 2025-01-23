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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('freelancer_id');
            $table->bigInteger('client_id');
            $table->double('price');
            $table->longText('description')->nullable();
            $table->string('deadline')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=pending, 1=active, 2=reject');
            $table->integer('revision')->default(0);
            $table->integer('revision_left')->default(0);
            $table->string('attachment')->nullable();
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
        Schema::dropIfExists('offers');
    }
};
