<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVerificationFilesToUserCompanyProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_company_profiles', function (Blueprint $table) {
            $table->string('public_service_doc')->nullable();
            $table->string('tax_id_doc')->nullable();
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
            $table->dropColumn('public_service_doc', 'tax_id_doc');
        });
    }
}
