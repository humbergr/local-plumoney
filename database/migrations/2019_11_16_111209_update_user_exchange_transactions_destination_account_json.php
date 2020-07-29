<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserExchangeTransactionsDestinationAccountJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_exchange_transactions', function (Blueprint $table) {
            $table->json('destination_account_json')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_exchange_transactions', function (Blueprint $table) {
            $table->dropColumn('destination_account_json');
        });
    }
}
