<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserCompanyProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_company_profiles', function (Blueprint $table) {
            $table->string('address_place_type')->nullable();
            $table->string('address_floor')->nullable();
            $table->string('address_place_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_company_profiles', function (Blueprint $table) {
            $table->dropColumn('address_place_type');
            $table->dropColumn('address_floor');
            $table->dropColumn('address_place_number');
        });
    }
}
