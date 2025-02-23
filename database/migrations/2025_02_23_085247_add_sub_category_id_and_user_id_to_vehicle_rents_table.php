<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('vehicle_rents', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_category_id')->nullable()->after('name');
            $table->foreign('sub_category_id')
                ->references('id')
                ->on('sub_categories')
                ->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable()->after('sub_category_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::table('vehicle_rents', function (Blueprint $table) {
            $table->dropForeign(['sub_category_id']);
            $table->dropColumn('sub_category_id');

            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
