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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('title');
            $table->string('slug');
            $table->bigInteger('category');
            $table->string('duration');
            $table->string('level');
            $table->longText('description');
            $table->string('type');
            $table->double('budget');
            $table->string('attachment');
            $table->tinyInteger('status')->default(0)->comment('0=pending, 1=approve');
            $table->tinyInteger('on_off')->default(1)->comment('1=on, 0=off');
            $table->tinyInteger('job_approve_request')->default(0)->comment('0=request for approve, 1=approve, 2=decline, 2=will change to 0 when the user edit the project.');
            $table->timestamp('last_seen')->nullable();
            $table->timestamp('last_apply_date')->nullable();
            $table->timestamps();
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_posts');
    }
};
