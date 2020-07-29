<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingTransactionIdOutgoingBtc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('outgoing_btcs', function (Blueprint $table) {
          $table->integer('transaction_id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('outgoing_btcs', function($table) {
          $table->dropColumn('transaction_id');
      });
    }
}
