<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActiveContactCachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('active_contact_caches', function (Blueprint $table) {
            $table->integer('contact_id');
            $table->string('account_name', 255);
            $table->json('json_data');
            $table->boolean('status')->default(false);
            $table->dateTime('anchor_date_localbtc')->nullable();
            $table->dateTime('anchor_date_est')->nullable();
            $table->boolean('process_fee')->default(true);
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
        Schema::dropIfExists('active_contact_caches');
    }
}
