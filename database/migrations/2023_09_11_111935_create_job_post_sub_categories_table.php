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
        Schema::create('job_post_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('job_post_id');
            $table->bigInteger('sub_category_id');
            $table->timestamps();
            $table->index(['job_post_id','sub_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_post_sub_categories');
    }
};
