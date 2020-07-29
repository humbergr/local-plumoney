<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDestinationAccountsAddNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('destination_accounts', function (Blueprint $table) {
            $table->string( 'name' )->nullable()->change();
            $table->string( 'lastname' )->nullable()->change();
            $table->string( 'email' )->nullable()->change();
            $table->string( 'phone_number' )->nullable()->change();
            $table->string( 'id_number' )->nullable()->change();
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
