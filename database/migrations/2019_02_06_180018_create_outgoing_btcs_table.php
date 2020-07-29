<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutgoingBtcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outgoing_btcs', function (Blueprint $table) {
          $table->increments('id');
          $table->float('amount_btc', 255, 8);
          $table->float('usd_price', 255, 2)->nullable();
          $table->datetime('released_date');
          $table->integer('contact_id')->nullable();
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
        Schema::dropIfExists('outgoing_btcs');
    }
}
