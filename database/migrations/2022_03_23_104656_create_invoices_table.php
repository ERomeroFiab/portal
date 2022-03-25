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

            $table->string('CONTRACT_NBER', 20)->nullable();
            $table->dateTime('DATE_EXPORT_SAGE')->nullable();
            $table->dateTime('DUE_DATE')->nullable();
            $table->string('ENTITY_NBER', 20)->nullable();
            $table->string('FIABILIS_GROUP_ENTITY', 50)->nullable();
            $table->string('ID_INVOICE', 32)->nullable();
            $table->dateTime('INVOICE_DATE')->nullable();
            $table->string('INVOICE_NBER', 20)->nullable();
            $table->string('NO_CONTRAT', 20)->nullable();
            $table->char('PAYE', 1)->nullable();
            $table->dateTime('PAYMENT_DATE')->nullable();
            $table->string('PID_CONTRAT', 32)->nullable();
            $table->string('PID_IDENTIFICATION', 32)->nullable();
            $table->string('PID_INVOICE', 32)->nullable();
            $table->string('PO', 30)->nullable();
            $table->string('PRODUCT', 200)->nullable();
            $table->char('SELECTION_EXPORT', 1)->nullable();
            $table->string('STATUS', 11)->nullable();
            $table->dateTime('SYS_DATE_CREATION')->nullable();
            $table->dateTime('SYS_DATE_MODIFICATION')->nullable();
            $table->time('SYS_HEURE_CREATION')->nullable();
            $table->time('SYS_HEURE_MODIFICATION')->nullable();
            $table->string('SYS_USER_CREATION', 20)->nullable();
            $table->string('SYS_USER_MODIFICATION', 20)->nullable();
            $table->double('TOTAL_AMOUNT_INVOICED')->nullable();
            $table->string('TYPE', 12)->nullable();
            $table->decimal('BALANCE_DUE', 12, 2)->nullable();
            $table->string('NOM_MODELE_WORD', 100)->nullable();

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
