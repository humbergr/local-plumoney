<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserWalletTransactionsPayed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_wallets_transactions', function (Blueprint $table) {
            $table->integer('is_payed')->nullable();
            $table->timestamp('payed_at')->nullable();
            $table->string('payed_by')->nullable();
            $table->integer('is_revised')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
