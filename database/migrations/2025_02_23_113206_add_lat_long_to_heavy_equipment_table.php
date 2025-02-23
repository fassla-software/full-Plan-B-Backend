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
        Schema::table('heavy_equipment', function (Blueprint $table) {
            $table->decimal('lat', 10, 7)->nullable()->after('category_id');
            $table->decimal('long', 10, 7)->nullable()->after('lat');
        });
    }

    public function down()
    {
        Schema::table('heavy_equipment', function (Blueprint $table) {
            $table->dropColumn(['lat', 'long']);
        });
    }
};
