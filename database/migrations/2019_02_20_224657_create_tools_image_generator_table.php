<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToolsImageGeneratorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tools_image_generator_ven_sale_usd', function (Blueprint $table) {
            $table->increments('id');
            $table->json('attributes')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('status')->nullable();
            $table->string('md5')->nullable();
            $table->timestamps();
        });

        Schema::create('tools_image_generator_ven_buy_usd', function (Blueprint $table) {
            $table->increments('id');
            $table->json('attributes')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('status')->nullable();
            $table->string('md5')->nullable();
            $table->timestamps();
        });

        Schema::create('tools_image_generator_country_offers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country_iso');
            $table->string('country_name');
            $table->string('country_currency');
            $table->json('attributes')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('status')->nullable();
            $table->string('md5')->nullable();
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
        Schema::dropIfExists('tools_image_generator_ven_sale_usd');
        Schema::dropIfExists('tools_image_generator_ven_buy_usd');
        Schema::dropIfExists('tools_image_generator_country_offers');
    }
}
