<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGirosCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('giros_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('price_id');
            $table->string('url');
            $table->string('currency');
            $table->string('iso');
            $table->string('country')->nullable();
            $table->string('payment_method');
            $table->json('json_data');
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
        Schema::dropIfExists('giros_countries');
    }
}
