<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCanceledContactCachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canceled_contact_caches', function (Blueprint $table) {
            $table->integer('contact_id');
            $table->string('account_name', 255);
            $table->json('json_data');
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
        Schema::dropIfExists('canceled_contact_caches');
    }
}
