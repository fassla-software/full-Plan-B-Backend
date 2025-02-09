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
          	$table->string('name')->nullable()->after('id');
            $table->unsignedBigInteger('sub_category_id')->nullable()->after('name');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('heavy_equipment_jobs', function (Blueprint $table) {
            $table->dropForeign(['sub_category_id']);
            $table->dropColumn('sub_category_id');
        });
    }

};
