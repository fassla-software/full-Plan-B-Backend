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
        Schema::table('identity_verifications', function (Blueprint $table) {
            $table->tinyInteger('status')
                ->nullable()
                ->after('back_image')
                ->comment('1=verified, 2=rejected');
            $table->tinyInteger('is_read')
                ->default(0)
                ->after('status')
                ->comment('1=read and 0=unread');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            //
        });
    }
};
