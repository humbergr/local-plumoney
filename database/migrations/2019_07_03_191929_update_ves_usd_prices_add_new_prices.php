<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVesUsdPricesAddNewPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ves_usd_prices', static function (Blueprint $table) {
            $table->float('sell_price_2', 255, 8)->default(0);
            $table->float('buy_price_2', 255, 8)->default(0);
            $table->json('sell_price_announces')->nullable();
            $table->json('buy_price_announces')->nullable();
            $table->json('sell_price_2_announces')->nullable();
            $table->json('buy_price_2_announces')->nullable();
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
