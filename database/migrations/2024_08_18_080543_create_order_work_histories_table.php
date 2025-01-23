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
        Schema::create('order_work_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('client_id');
            $table->integer('freelancer_id');
            $table->integer('job_id')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->date('only_start_date')->nullable();
            $table->date('only_end_date')->nullable();
            $table->time('hours_worked');
            $table->integer('seconds')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_work_histories');
    }
};
