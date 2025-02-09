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
        Schema::table('heavy_equipment_jobs', function (Blueprint $table) {
            $table->string('hour')->nullable()->after('work_site_location');
            $table->string('day')->nullable()->after('hour');
            $table->string('month')->nullable()->after('day');
        });
    }

    public function down(): void
    {
        Schema::table('heavy_equipment_jobs', function (Blueprint $table) {
            $table->dropColumn('hour');
          	$table->dropColumn('day');
          	$table->dropColumn('month');
        });
    }

};
