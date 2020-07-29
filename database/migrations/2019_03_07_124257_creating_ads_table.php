<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatingAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('advertisements', function (Blueprint $table) {
        $table->increments('id');
        $table->bigInteger('ad_id');
        $table->integer('trader_id');
        $table->bigInteger('contact_id')->nullable();
        $table->float('amount', 255, 2);
        $table->float('rate', 255, 2);
        $table->string('currency');
        $table->string('ad_username');
        $table->string('trade_type');
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
      Schema::dropIfExists('advertisements');
    }
}
