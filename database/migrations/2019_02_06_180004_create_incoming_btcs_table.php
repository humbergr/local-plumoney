<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomingBtcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incoming_btcs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id');
            $table->float('amount_btc', 255, 8);
            $table->float('usd_price', 255, 2)->nullable();
            $table->datetime('released_date');
            $table->boolean('was_used')->default(0);
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
        Schema::dropIfExists('incoming_btcs');
    }
}
