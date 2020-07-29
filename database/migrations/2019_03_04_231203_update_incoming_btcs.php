<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIncomingBtcs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incoming_btcs', function (Blueprint $table) {
            $table->dropColumn('hold_spend');
            $table->dropColumn('hold_remaining');
            $table->dropColumn('hold_by');
            $table->dropColumn('reserved_to');
        });
        Schema::table('incoming_btcs', function (Blueprint $table) {
            $table->json('hold_spend')->nullable();
            $table->json('hold_by')->nullable();
            $table->json('reserved_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
