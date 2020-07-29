<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIncomingOutgoingBtcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incoming_btcs', function (Blueprint $table) {
            $table->string('account_name', 255)->nullable();
        });
        Schema::table('outgoing_btcs', function (Blueprint $table) {
            $table->string('account_name', 255)->nullable();
        });
        Schema::table('error_transactions', function (Blueprint $table) {
            $table->string('account_name', 255)->nullable();
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
