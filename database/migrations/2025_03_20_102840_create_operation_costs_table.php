<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('operation_costs', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('operation_type')->unsigned()->comment('1=make request,2=make offer,3=update request,4=update offer,5=delete request,6=delete offer,7=make ads');
            $table->string('category_slug', 255)->after('operation_type')->comment('Slug of the category');
            $table->bigInteger('cost')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_costs');
    }
};
