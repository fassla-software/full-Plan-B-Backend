<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sub_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->string('sub_category', 255);
            $table->string('short_description', 255);
            $table->string('slug', 255);
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedBigInteger('sub_category_id');
            $table->tinyInteger('status')->default(1)->comment('0=inactive 1=active');
            $table->string('image', 255)->nullable();
            $table->timestamps();

            $table->foreign('sub_category_id')
                ->references('id')
                ->on('sub_categories')
                ->onDelete('cascade');
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('sub_sub_categories');
    }
};
