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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->comment('client id');
            $table->bigInteger('freelancer_id');
            $table->bigInteger('identity')->comment('project_id or job_id');
            $table->string('is_project_job')->comment('project or job');
            $table->string('is_basic_standard_premium_custom')->nullable()->comment('project type');
            $table->string('is_fixed_hourly')->nullable()->comment('fixed or hourly');
            $table->tinyInteger('is_custom')->default(0)->comment('1=custom');
            $table->tinyInteger('status')->default(0)->comment('0=pending, 1=active, 2=delivered, 3=complete, 4=cancel, 5=decline by frl, 6=suspend by ad, 7=hold by ad');
            $table->string('revision')->nullable();
            $table->string('delivery_time')->nullable();
            $table->longText('description')->nullable();
            $table->double('price');
            $table->string('commission_type');
            $table->double('commission_charge');
            $table->double('commission_amount')->default(0);
            $table->string('transaction_type')->nullable();
            $table->double('transaction_charge')->default(0);
            $table->double('transaction_amount')->default(0);
            $table->double('payable_amount')->default(0);
            $table->double('refund_amount')->default(0);
            $table->tinyInteger('refund_status')->default(0)->comment('0=pending, 1=paid');
            $table->double('total_hour')->nullable();
            $table->string('payment_gateway');
            $table->string('payment_status');
            $table->string('transaction_id')->nullable();
            $table->string('manual_payment_image')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
