<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserRegistrationCodesAddDisabled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_registration_codes', function (Blueprint $table) {
            $table->tinyInteger('is_disabled')->nullable();
            $table->timestamp('disabled_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_registration_codes', function (Blueprint $table) {
            $table->dropColumn('is_disabled', 'disabled_at');
        });
    }
}
