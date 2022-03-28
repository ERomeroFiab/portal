<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGestionesHistoricasTable extends Migration
{
    public function up()
    {
        Schema::create('gestiones_historicas', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('motivo')->nullable()->comment('tabla missions, columna PRODUIT');
            $table->string('gestion')->nullable()->comment('tabla mission_motive_eco, columna SOUS_MOTIF_2');
            $table->string('periodo_gestion')->nullable()->comment('tabla mission_motive_eco, columna SOUS_MOTIF_1');
            $table->string('fecha_deposito')->nullable()->comment('tabla mission_motive_eco, columna DATE_PREVISIONNELLE');
            $table->string('monto_depositado')->nullable()->comment('tabla mission_motive_eco, columna ECO_PRESENTEE');
            $table->string('honorarios_fiabilis')->nullable()->comment('tabla invoice_ligne, columna AMOUNT');
            $table->string('montos_facturados')->nullable()->comment('tabla invoice_ligne, columna AMOUNT');
            $table->string('monto_a_facturar')->nullable()->comment('30% de la columna ECO_PRESENTEE (monto depositado)');
            $table->string('origin')->nullable()->comment('ST (Silvertool), CN (excel histórico)');

            $table->unsignedBigInteger('razon_social_id')->nullable();
            $table->foreign('razon_social_id')->references('id')->on('razon_socials')->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gestiones_historicas');
    }
}
