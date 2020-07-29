<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingWalletTransactionIdToExchangePaymentDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_payment_datas', function (Blueprint $table) {
            $table->integer('wallet_transaction_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchange_payment_datas', function (Blueprint $table) {
            $table->dropColumn('wallet_transaction_id');
        });
    }
}
