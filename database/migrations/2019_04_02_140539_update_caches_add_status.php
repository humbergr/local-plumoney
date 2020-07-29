<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCachesAddStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('canceled_contact_caches', function (Blueprint $table) {
            $table->smallInteger('status')->default(0);
        });

        Schema::table('closed_contact_caches', function (Blueprint $table) {
            $table->smallInteger('status')->default(0);
        });

        Schema::create('released_contact_caches', function (Blueprint $table) {
            $table->integer('contact_id');
            $table->string('account_name', 255);
            $table->json('json_data');
            $table->smallInteger('status')->default(0);
            $table->timestamps();

            $table->primary('contact_id');
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
