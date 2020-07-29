<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserPersonProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_person_profiles', function (Blueprint $table) {
            $table->string('second_name')->nullable();
            $table->string('second_last_name')->nullable();
            $table->string('local_phone')->nullable();
            $table->string('address_place_type')->nullable();
            $table->string('address_floor')->nullable();
            $table->string('address_place_number')->nullable();
            $table->string('id_type')->nullable();
            $table->string('id_number')->nullable();
            $table->dateTime('id_creation_date')->nullable();
            $table->dateTime('id_expiration_date')->nullable();
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
            $table->dropColumn('second_name');
            $table->dropColumn('second_last_name');
            $table->dropColumn('local_phone');
            $table->dropColumn('address_place_type');
            $table->dropColumn('address_floor');
            $table->dropColumn('address_place_number');
            $table->dropColumn('id_type');
            $table->dropColumn('id_number');
            $table->dropColumn('id_creation_date');
            $table->dropColumn('id_expiration_date');
        });
    }
}
