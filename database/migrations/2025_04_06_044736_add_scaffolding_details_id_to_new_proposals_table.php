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
            $table->foreignId('scaffolding_offer_detail_id')
                ->nullable()
                ->constrained('scaffolding_offer_details')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('new_proposals', function (Blueprint $table) {
            $table->dropForeign(['scaffolding_offer_detail_id']);
            $table->dropColumn('scaffolding_offer_detail_id');
        });
    }
};
