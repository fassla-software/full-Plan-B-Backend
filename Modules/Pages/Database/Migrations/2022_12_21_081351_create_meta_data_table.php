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
        Schema::create('meta_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meta_taggable_id');
            $table->string('meta_taggable_type');
            $table->string('meta_title')->nullable();
            $table->string('meta_tags')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('facebook_meta_tags')->nullable();
            $table->text('facebook_meta_description')->nullable();
            $table->string('facebook_meta_image')->nullable();
            $table->string('twitter_meta_tags')->nullable();
            $table->text('twitter_meta_description')->nullable();
            $table->string('twitter_meta_image')->nullable();
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
        Schema::dropIfExists('meta_data');
    }
};
