<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePromotorsPaymentsTableWalletTId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotor_payments', function (Blueprint $table) {
            $table->integer('wallet_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotor_payments', function (Blueprint $table) {
            $table->dropColumn(['wallet_transaction_id']);
        });
    }
}
