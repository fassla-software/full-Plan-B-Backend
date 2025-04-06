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
        Schema::table('new_proposals', function (Blueprint $table) {
            $table->foreignId('generator_offer_detail_id')
                ->nullable()
                ->constrained('generator_offer_details')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('new_proposals', function (Blueprint $table) {
            $table->dropForeign(['generator_offer_detail_id']);
            $table->dropColumn('generator_offer_detail_id');
        });
    }
};
