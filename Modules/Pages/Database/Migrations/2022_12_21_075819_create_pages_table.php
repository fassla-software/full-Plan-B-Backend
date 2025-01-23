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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('slug')->nullable();
            $table->longText('page_content')->nullable();
            $table->string('page_builder_status')->nullable();
            $table->string('layout')->nullable();
            $table->string('page_class')->nullable();
            $table->string('breadcrumb_status')->nullable();
            $table->string('navbar_variant')->nullable();
            $table->string('footer_variant')->nullable();
            $table->string('visibility')->nullable();
            $table->tinyInteger('status')->nullable()->comment('1-active, 0-inactive');
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
        Schema::dropIfExists('pages');
    }
};
