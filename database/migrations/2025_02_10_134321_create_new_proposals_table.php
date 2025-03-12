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
        Schema::create('new_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('request_id')->constrained('requests')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->enum('per', ['day', 'month', 'hour']);
            $table->string('current_location');
            $table->timestamp('offer_ends_at');
            $table->text('other_terms')->nullable();
            $table->boolean('isSeen')->default(0)->comment('0=unseen,1=seen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_proposals');
    }
};
