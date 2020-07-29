<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRegistrationCodeUsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_registration_code_uses', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('code_id')->unsigned();
            $table->integer('user_id')->unsigned();
        });

        Schema::table('user_registration_code_uses', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('code_id')->references('id')->on('user_registration_codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_registration_code_uses');
    }
}
