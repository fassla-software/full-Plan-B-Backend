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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email');
            $table->tinyInteger('is_email_verified',false,true)->default(0)->comment('0: not verified, 1:verified');
            $table->string('phone')->nullable();
            $table->string('role')->default('editor')->comment('1:super admin, 2:admin, 3:manager, 4:editor, 5:supporter 6:employee');
            $table->string('image')->nullable();
            $table->string('password');
            $table->tinyInteger('status')->default(1)->comment('0:active, 1:inactive');
            $table->string('designation')->nullable();
            $table->text('about')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
};
