<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCurrencyWalletsTable extends Migration
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
        });
        Schema::table('currency_wallets', function (Blueprint $table) {
            $table->float('balance', 255, 8)->default(0);
            $table->float('available', 255, 8)->default(0);
            $table->integer('user_id')->nullable();
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
