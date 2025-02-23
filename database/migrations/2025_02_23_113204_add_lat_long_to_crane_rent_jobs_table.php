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
        Schema::table('crane_rent_jobs', function (Blueprint $table) {
            $table->decimal('lat', 10, 7)->nullable()->after('category_id');
            $table->decimal('long', 10, 7)->nullable()->after('lat');
            $table->decimal('search_radius', 8, 2)->nullable()->after('long');

        });
    }

    public function down()
    {
        Schema::table('crane_rent_jobs', function (Blueprint $table) {
            $table->dropColumn(['lat', 'long', 'search_radius']);
        });
    }
};
