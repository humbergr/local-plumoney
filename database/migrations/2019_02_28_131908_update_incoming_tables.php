<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIncomingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incoming_btcs', function (Blueprint $table) {
            $table->dateTime('localbtc_released_date')->nullable();
        });
        Schema::table('outgoing_btcs', function (Blueprint $table) {
            $table->dateTime('localbtc_released_date')->nullable();
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->dateTime('localbtc_released_date')->nullable();
        });
        Schema::table('error_transactions', function (Blueprint $table) {
            $table->dateTime('released_date')->nullable();
            $table->dateTime('localbtc_released_date')->nullable();
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
