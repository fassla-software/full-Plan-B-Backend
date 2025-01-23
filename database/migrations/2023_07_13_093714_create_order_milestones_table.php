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
        Schema::create('order_milestones', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->string('title');
            $table->text('description');
            $table->double('price');
            $table->string('deadline');
            $table->tinyInteger('status')->default(0)->comment('0=pending, 1=active, 2=complete, 3=cancel');
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
        Schema::dropIfExists('order_milestones');
    }
};
