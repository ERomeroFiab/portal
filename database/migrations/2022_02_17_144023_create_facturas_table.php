<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('monto');
            $table->string('status')->default('Pendiente')->comment('Pendiente, En Proceso, Pagada');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facturas');
    }
}
