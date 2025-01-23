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
        Schema::table('jobs', function (Blueprint $table) {
            $table->tinyInteger('job_approve_request')->default(0)->comment('0=request for approve, 1=approve, 2=decline, 2=2 will change to 0 when the user edit the project.');//->after('on_off');
            $table->timestamp('last_seen')->nullable()->after('job_approve_request');
            $table->timestamp('last_apply_date')->nullable()->after('last_seen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            //
        });
    }
};
