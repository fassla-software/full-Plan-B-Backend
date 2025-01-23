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
        Schema::create('freelancer_notifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('identity');
            $table->bigInteger('freelancer_id');
            $table->string('type');
            $table->string('message');
            $table->string('is_read')->default('unread');
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
        Schema::dropIfExists('freelancer_notifications');
    }
};
