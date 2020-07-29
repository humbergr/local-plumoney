<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserWalletsTransactionsAddExhcangeRelated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_wallets_transactions', function (Blueprint $table) {
            $table->boolean('exchange_related')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_wallets_transactions', function (Blueprint $table) {
            $table->dropColumn('exchange_related');
        });
    }
}
