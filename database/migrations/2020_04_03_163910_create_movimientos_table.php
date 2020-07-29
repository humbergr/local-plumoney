<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedInteger('banco_id');
            $table->foreign('banco_id')->references('id')->on('bancos');

            $table->unsignedInteger('cuenta_id');
            $table->foreign('cuenta_id')->references('id')->on('cuentas');

            $table->string('tipo');
            $table->string('monto');
            $table->string('moneda');
 
            $table->text('descripcion')->nullable();
            $table->text('operacion');
            $table->text('tasa');
            $table->text('monto_usd');
            $table->text('capture')->nullable();
            $table->date('emision')->nullable();
             
 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimientos');
    }
}
