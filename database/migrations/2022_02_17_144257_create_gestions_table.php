<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGestionsTable extends Migration
{
    public function up()
    {
        Schema::create('gestions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('razon_social_id')->nullable();
            $table->foreign('razon_social_id')->references('id')->on('razon_socials')->onDelete('set null');

            $table->unsignedBigInteger('factura_id')->nullable();
            $table->foreign('factura_id')->references('id')->on('facturas')->onDelete('set null');

            $table->string('glosa')->nullable();
            $table->integer('monto_gestionado')->nullable();
            $table->integer('monto_aprobado')->nullable();
            $table->integer('fee')->nullable();
            $table->integer('monto_factura')->nullable();
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_cierre')->nullable();
            $table->timestamp('fecha_deposito')->nullable();
            $table->integer('honorarios_fiabilis')->nullable();
            $table->string('tipo')->nullable()->comment('Oportunidades, Recuperación, Regularizaciones');
            $table->string('motivo')->nullable()->comment('SIS Cuprum, Exceso Colmena, Ahorro SIS, etc');
            $table->string('producto')->nullable();
            $table->string('status')->nullable()->comment('Pendiente o Finalizada');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gestions');
    }
}
