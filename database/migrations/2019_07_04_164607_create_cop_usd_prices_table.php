<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCopUsdPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cop_usd_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->float('sell_price', 255, 8)->default(0);
            $table->float('buy_price', 255, 8)->default(0);
            $table->json('sell_price_announces')->nullable();
            $table->json('buy_price_announces')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cop_usd_prices');
    }
}
