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
        Schema::create('commas_consume', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_subscription_id');
            $table->unsignedBigInteger('operation_cost_id');
            $table->integer('consumed_limit')->default(0);
            $table->integer('remaining_limit')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_subscription_id')->references('id')->on('user_subscriptions')->onDelete('cascade');
            $table->foreign('operation_cost_id')->references('id')->on('operation_costs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comma_consumes');
    }
};
