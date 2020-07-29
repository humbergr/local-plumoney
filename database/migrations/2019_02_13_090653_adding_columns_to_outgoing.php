<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingColumnsToOutgoing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('outgoing_btcs', function (Blueprint $table) {
          $table->float('fee_btc', 255, 8);
          $table->float('total_btc', 255, 8);
          $table->float('profit', 255, 2);
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
          $table->dropColumn('fee_btc');
          $table->dropColumn('total_btc');
          $table->dropColumn('profit');
      });
    }
}
