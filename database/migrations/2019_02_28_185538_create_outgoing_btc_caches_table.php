<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutgoingBtcCachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outgoing_btc_caches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_name', 255)->nullable();
            $table->json('contactInfo')->nullable();
            $table->json('walletTransaction')->nullable();
            $table->dateTime('localbtc_released_date')->nullable();
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
        Schema::dropIfExists('outgoing_btc_caches');
    }
}
