<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionsTable extends Migration
{
    public function up()
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('COORDINATOR', 20)->nullable();
            $table->string('CURRENT_STEP', 50)->nullable();
            $table->date('DATE_DEBUT')->nullable();
            $table->date('DATE_DEBUT_ANALYSE')->nullable();
            $table->date('DATE_FIN_ANALYSE')->nullable();
            $table->date('DATE_FIN_MISSION')->nullable();
            $table->date('DEADLINE')->nullable();
            $table->string('FAMILLE', 30)->nullable();
            $table->string('ID_MISSION', 32)->nullable();
            $table->string('NO_CONTRAT', 20)->nullable();
            $table->string('NO_MISSION', 20)->nullable();
            $table->string('PID_CONTRAT', 32)->nullable();
            $table->string('PID_CONTRAT_DETAIL_PRODUIT', 32)->nullable();
            $table->string('PID_IDENTIFICATION', 32)->nullable();
            $table->double('POURCENTAGE')->nullable();
            $table->string('PRIORITY', 1)->nullable();
            $table->string('PRODUIT', 50)->nullable();
            $table->string('PROJECT_MANAGER', 20)->nullable();
            $table->char('STEPS_MANAGED_FROM_MOTIVE', 1)->nullable();
            $table->date('SYS_DATE_CREATION')->nullable();
            $table->date('SYS_DATE_MODIFICATION')->nullable();
            $table->time('SYS_HEURE_CREATION')->nullable();
            $table->time('SYS_HEURE_MODIFICATION')->nullable();
            $table->string('SYS_USER_CREATION', 20)->nullable();
            $table->string('SYS_USER_MODIFICATION', 20)->nullable();
            $table->double('VP_FEES')->nullable();
            $table->string('VP_N_CONTRAT_CADRE', 15)->nullable();
            $table->string('VP_N_CONTRAT_PARTIEL', 15)->nullable();
            $table->string('VP_PRODUCT', 60)->nullable();

            $table->unsignedBigInteger('razon_social_id')->nullable();
            $table->foreign('razon_social_id')->references('id')->on('razon_socials')->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('missions');
    }
}
