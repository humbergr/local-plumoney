<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCachesAddAnchorDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('canceled_contact_caches', function (Blueprint $table) {
            $table->dateTime('anchor_date_localbtc')->nullable();
            $table->dateTime('anchor_date_est')->nullable();
        });

        Schema::table('closed_contact_caches', function (Blueprint $table) {
            $table->dateTime('anchor_date_localbtc')->nullable();
            $table->dateTime('anchor_date_est')->nullable();
        });

        Schema::table('released_contact_caches', function (Blueprint $table) {
            $table->dateTime('anchor_date_localbtc')->nullable();
            $table->dateTime('anchor_date_est')->nullable();
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
