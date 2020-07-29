<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserWalletsTransactionsFiatAmmounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_wallets_transactions', function (Blueprint $table) {
            $table->string('sender_fiat')->nullable();
            $table->float('sender_fiat_amount', 255, 2)->default(0);
            $table->string('receiver_fiat')->nullable();
            $table->float('receiver_fiat_amount', 255, 2)->default(0);
            $table->string('payment_way')->nullable();
            $table->string('payment_source')->nullable();
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
            $table->dropColumn(
                'sender_fiat',
                'sender_fiat_amount',
                'receiver_fiat',
                'receiver_fiat_amount',
                'payment_way',
                'payment_source'
            );
        });
    }
}
