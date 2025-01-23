<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('freeze_withdraw')->nullable()->after('firebase_device_token');
            $table->string('freeze_project')->nullable()->after('freeze_withdraw');
            $table->string('freeze_job')->nullable()->after('freeze_project');
            $table->string('freeze_chat')->nullable()->after('freeze_job');
            $table->string('freeze_order_create')->nullable()->after('freeze_chat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
