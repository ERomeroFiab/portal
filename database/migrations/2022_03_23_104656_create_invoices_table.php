<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('CONTRACT_NBER');
            $table->dateTime('DATE_EXPORT_SAGE')->nullable();
            $table->date('DUE_DATE')->nullable();
            $table->string('ENTITY_NBER');
            $table->string('FIABILIS_GROUP_ENTITY');
            $table->string('ID_INVOICE');
            $table->date('INVOICE_DATE')->nullable();
            $table->string('INVOICE_NBER');
            $table->string('NO_CONTRAT');
            $table->char('PAYE');
            $table->date('PAYMENT_DATE')->nullable();
            $table->string('PID_CONTRAT');
            $table->string('PID_IDENTIFICATION');
            $table->string('PID_INVOICE');
            $table->string('PO');
            $table->string('PRODUCT');
            $table->char('SELECTION_EXPORT');
            $table->string('STATUS');
            $table->date('SYS_DATE_CREATION')->nullable();
            $table->date('SYS_DATE_MODIFICATION')->nullable();
            $table->time('SYS_HEURE_CREATION');
            $table->time('SYS_HEURE_MODIFICATION');
            $table->string('SYS_USER_CREATION');
            $table->string('SYS_USER_MODIFICATION');
            $table->double('TOTAL_AMOUNT_INVOICED');
            $table->string('TYPE');
            $table->decimal('BALANCE_DUE');
            $table->string('NOM_MODELE_WORD');
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
        Schema::dropIfExists('invoices');
    }
}
