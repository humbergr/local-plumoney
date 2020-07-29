<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingErrorType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('error_transactions', function (Blueprint $table) {
          $table->boolean('not_funds')->default(false);
          $table->boolean('not_usd_price')->default(false);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('error_transactions', function($table) {
          $table->dropColumn('not_funds');
          $table->dropColumn('not_usd_price');
      });
    }
}
