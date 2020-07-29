<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ad_id');
            $table->string('local_contact_id');
            $table->integer('trader_id');
            $table->float('fiat_amount', 255, 2);
            $table->float('amount_btc', 255, 8);
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
        Schema::dropIfExists('local_contacts');
    }
}
