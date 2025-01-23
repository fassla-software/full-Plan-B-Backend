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
        if(Schema::hasTable('promotion_project_lists')) {
            Schema::table('promotion_project_lists', function (Blueprint $table) {
                $table->string('email_send')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotion_project_lists', function (Blueprint $table) {
            //
        });
    }
};
