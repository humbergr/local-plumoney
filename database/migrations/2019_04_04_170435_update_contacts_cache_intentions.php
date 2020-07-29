<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateContactsCacheIntentions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('canceled_contact_caches', function (Blueprint $table) {
            $table->boolean('is_buying')->nullable();
            $table->boolean('is_selling')->nullable();
        });

        Schema::table('closed_contact_caches', function (Blueprint $table) {
            $table->boolean('is_buying')->nullable();
            $table->boolean('is_selling')->nullable();
        });

        Schema::table('released_contact_caches', function (Blueprint $table) {
            $table->boolean('is_buying')->nullable();
            $table->boolean('is_selling')->nullable();
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
