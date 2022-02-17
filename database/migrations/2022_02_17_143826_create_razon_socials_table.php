<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRazonSocialsTable extends Migration
{
    public function up()
    {
        Schema::create('razon_socials', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('set null');

            $table->string('nombre');
            $table->string('rut');
            $table->string('direccion')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('razon_socials');
    }
}
