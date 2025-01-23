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
        Schema::create('job_proposals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('job_id');
            $table->bigInteger('freelancer_id');
            $table->bigInteger('client_id');
            $table->double('amount');
            $table->string('duration');
            $table->text('cover_letter');
            $table->string('attachment')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=pending, 1=accept, 2=reject');
            $table->tinyInteger('is_hired')->default(0)->comment('0=no, 1=yes');
            $table->tinyInteger('is_short_listed')->default(0)->comment('0=no, 1=yes');
            $table->tinyInteger('is_interview_take')->default(0)->comment('0=no, 1=yes');
            $table->tinyInteger('is_view')->default(0)->comment('0=no, 1=yes');
            $table->tinyInteger('is_rejected')->default(0)->comment('0=no, 1=yes');
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
        Schema::dropIfExists('job_proposals');
    }
};
