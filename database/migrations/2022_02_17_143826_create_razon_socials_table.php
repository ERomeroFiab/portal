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
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('set null')->onUpdate('cascade');

            $table->string('nombre');
            $table->string('rut');
            $table->string('ciudad')->nullable();
            $table->integer('codigo_postal')->nullable();
            $table->string('direccion')->nullable();
            $table->string('numero_de_cuenta_bancaria')->nullable();
            $table->string('banco')->nullable();
            $table->string('tipo_de_cuenta')->nullable();
            $table->string('principal')->nullable()->comment('true or null');
            $table->string('status')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('razon_socials');
    }
}
