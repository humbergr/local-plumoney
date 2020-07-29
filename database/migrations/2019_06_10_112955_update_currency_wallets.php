<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCurrencyWallets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currency_wallets', function (Blueprint $table) {
            $table->dropColumn('balance');
            $table->dropColumn('available');
            $table->dropColumn('locked');
        });

        Schema::table('currency_wallets', function (Blueprint $table) {
            $table->string('hash')->nullable();
            $table->integer('status')->nullable();
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
