<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotedetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotedetalles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lote_id');
            $table->string('lote');
            $table->string('monto');
            $table->string('currency');
            $table->string('operacion');
            $table->string('tasa');
            $table->integer('banco_id');
            $table->integer('movimiento_id')->nullable();
            $table->text('comentarios')->nullable();
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
        Schema::dropIfExists('lotedetalles');
    }
}
