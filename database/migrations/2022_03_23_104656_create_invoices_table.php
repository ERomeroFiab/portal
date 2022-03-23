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
            $table->string('CONTRACT_NBER', 20)->default('')->index('CONTRACT_NBER');
            $table->dateTime('DATE_EXPORT_SAGE')->nullable()->index('DATE_EXPORT_SAGE');
            $table->date('DUE_DATE')->nullable();
            $table->string('ENTITY_NBER', 20)->default('')->index('ENTITY_NBER');
            $table->string('FIABILIS_GROUP_ENTITY', 50)->default('');
            $table->string('ID_INVOICE', 32)->default('')->primary();
            $table->date('INVOICE_DATE')->nullable();
            $table->string('INVOICE_NBER', 20)->default('')->index('INVOICE_NBER');
            $table->string('NO_CONTRAT', 20)->default('')->index('NO_CONTRAT');
            $table->char('PAYE', 1)->default('');
            $table->date('PAYMENT_DATE')->nullable();
            $table->string('PID_CONTRAT', 32)->default('')->index('PID_CONTRAT');
            $table->string('PID_IDENTIFICATION', 32)->default('')->index('PID_IDENTIFICATION');
            $table->string('PID_INVOICE', 32)->default('')->index('PID_INVOICE');
            $table->string('PO', 30)->default('');
            $table->string('PRODUCT', 200)->default('')->index('PRODUCT');
            $table->char('SELECTION_EXPORT', 1)->default('');
            $table->string('STATUS', 11)->default('')->index('STATUS');
            $table->date('SYS_DATE_CREATION')->nullable()->index('SYS_DATE_CREATION');
            $table->date('SYS_DATE_MODIFICATION')->nullable()->index('SYS_DATE_MODIFICATION');
            $table->time('SYS_HEURE_CREATION')->default('00:00:00')->index('SYS_HEURE_CREATION');
            $table->time('SYS_HEURE_MODIFICATION')->default('00:00:00')->index('SYS_HEURE_MODIFICATION');
            $table->string('SYS_USER_CREATION', 20)->default('')->index('SYS_USER_CREATION');
            $table->string('SYS_USER_MODIFICATION', 20)->default('')->index('SYS_USER_MODIFICATION');
            $table->double('TOTAL_AMOUNT_INVOICED')->default(0);
            $table->string('TYPE', 12)->default('')->index('TYPE');
            $table->decimal('BALANCE_DUE', 12, 2)->default(0.00);
            $table->string('NOM_MODELE_WORD', 100)->default('');
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
