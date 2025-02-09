<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('locale'); // en, ar, etc.
            $table->string('name'); // Localized name
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_translations');
    }
};
