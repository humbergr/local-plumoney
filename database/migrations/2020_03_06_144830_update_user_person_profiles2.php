<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserPersonProfiles2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_person_profiles', function (Blueprint $table) {
            $table->boolean('datos_verified')->default(false);
            $table->boolean('identity_verified')->default(false);
            $table->string('id_confirmation_back')->nullable();
            $table->text('gps')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_person_profiles', function (Blueprint $table) {
            $table->dropColumn('datos_verified');
            $table->dropColumn('identity_verified');
            $table->dropColumn('id_confirmation_back');
            $table->dropColumn('gps')->nullable();

        });
    }
}
