<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceLignesTable extends Migration
{
    public function up()
    {
        Schema::create('invoice_lignes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->double('AMOUNT')->nullable();
            $table->string('CN_CHOICE', 15)->nullable();
            $table->date('CN_ESTIMATED_DATE')->nullable();
            $table->binary('COMMENTAIRE')->nullable();
            $table->char('DISPLAY_NEW_FEE', 1)->nullable();
            $table->double('ECO_AMOUNT')->nullable();
            $table->double('FEES')->nullable();
            $table->char('FEE_INCLUDES_VAT', 1)->nullable();
            $table->string('ID_INVOICE_LIGNE', 32)->nullable();
            $table->string('MOTIVE', 70)->nullable();
            $table->double('NO_LIGNE')->nullable();
            $table->string('PID_INVOICE', 32)->nullable();
            $table->string('PID_INVOICE_LIGNE', 32)->nullable();
            $table->string('PID_MISSION_MOTIVE_ECO', 32)->nullable();
            $table->string('PRODUCT', 50)->nullable();
            $table->string('SUB_MOTIVE1', 50)->nullable();
            $table->string('SUB_MOTIVE2', 50)->nullable();
            $table->date('SYS_DATE_CREATION')->nullable();
            $table->date('SYS_DATE_MODIFICATION')->nullable();
            $table->time('SYS_HEURE_CREATION')->nullable();
            $table->time('SYS_HEURE_MODIFICATION')->nullable();
            $table->string('SYS_USER_CREATION', 20)->nullable();
            $table->string('SYS_USER_MODIFICATION', 20)->nullable();
            $table->string('TYPE', 10)->nullable();
            $table->string('YEAR', 10)->nullable();

            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('set null')->onUpdate('cascade');
            
            $table->unsignedBigInteger('mission_motive_eco_id')->nullable();
            $table->foreign('mission_motive_eco_id')->references('id')->on('mission_motive_ecos')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedBigInteger('razon_social_id')->nullable();
            $table->foreign('razon_social_id')->references('id')->on('razon_socials')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedBigInteger('mission_motive_id')->nullable();
            $table->foreign('mission_motive_id')->references('id')->on('mission_motives')->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_lignes');
    }
}
