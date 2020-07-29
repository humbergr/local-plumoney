<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingRevisedFoundsToUserExchangeTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_exchange_transactions', function (Blueprint $table) {
            $table->boolean('revised_founds')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_exchange_transctions', function (Blueprint $table) {
            $table->dropColumn('revised_founds');
        });
    }
}
