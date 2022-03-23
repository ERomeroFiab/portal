<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('CONTRACT_NBER')->nullable();
            $table->dateTime('DATE_EXPORT_SAGE')->nullable();
            $table->dateTime('DUE_DATE')->nullable();
            $table->string('ENTITY_NBER')->nullable();
            $table->string('FIABILIS_GROUP_ENTITY')->nullable();
            $table->string('ID_INVOICE')->nullable();
            $table->dateTime('INVOICE_DATE')->nullable();
            $table->string('INVOICE_NBER')->nullable();
            $table->string('NO_CONTRAT')->nullable();
            $table->string('PAYE')->nullable();
            $table->dateTime('PAYMENT_DATE')->nullable();
            $table->string('PID_CONTRAT')->nullable();
            $table->string('PID_IDENTIFICATION')->nullable();
            $table->string('PID_INVOICE')->nullable();
            $table->string('PO')->nullable();
            $table->string('PRODUCT')->nullable();
            $table->string('SELECTION_EXPORT')->nullable();
            $table->string('STATUS')->nullable();
            $table->dateTime('SYS_DATE_CREATION')->nullable();
            $table->dateTime('SYS_DATE_MODIFICATION')->nullable();
            $table->time('SYS_HEURE_CREATION')->nullable();
            $table->time('SYS_HEURE_MODIFICATION')->nullable();
            $table->string('SYS_USER_CREATION')->nullable();
            $table->string('SYS_USER_MODIFICATION')->nullable();
            $table->double('TOTAL_AMOUNT_INVOICED')->nullable();
            $table->string('TYPE')->nullable();
            $table->decimal('BALANCE_DUE', 12, 2)->nullable();
            $table->string('NOM_MODELE_WORD')->nullable();

            $table->unsignedBigInteger('razon_social_id')->nullable();
            $table->foreign('razon_social_id')->references('id')->on('razon_socials')->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
