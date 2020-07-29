<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserExchangeNWalletsTransactionsAddForcedTransfer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_exchange_transactions', function (Blueprint $table) {
            $table->smallInteger('forced_transfer')->nullable();
            $table->json('previous_assignations')->nullable();
            $table->json('forced_transfer_dates')->nullable();
        });

        Schema::table('user_wallets_transactions', function (Blueprint $table) {
            $table->smallInteger('forced_transfer')->nullable();
            $table->json('previous_assignations')->nullable();
            $table->json('forced_transfer_dates')->nullable();
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
            $table->dropColumn('forced_transfer');
            $table->dropColumn('previous_assignations');
            $table->dropColumn('forced_transfer_dates');
        });

        Schema::table('user_wallets_transactions', function (Blueprint $table) {
            $table->dropColumn('forced_transfer');
            $table->dropColumn('previous_assignations');
            $table->dropColumn('forced_transfer_dates');
        });
    }
}
