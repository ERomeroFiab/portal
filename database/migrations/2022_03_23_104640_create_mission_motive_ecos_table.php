<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionMotiveEcosTable extends Migration
{
    public function up()
    {
        Schema::create('mission_motive_ecos', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->date('DATE_PREVISIONNELLE')->nullable();
            $table->double('ECO_ABANDONNEE')->nullable();
            $table->double('ECO_A_FACTURER')->nullable();
            $table->double('ECO_ECART')->nullable();
            $table->double('ECO_PRESENTEE')->nullable();
            $table->double('ECO_VALIDEE')->nullable();
            $table->string('ID_MISSION_MOTIVE_ECO')->nullable();
            $table->binary('NOTES')->nullable();
            $table->double('PACKAGE')->nullable();
            $table->string('PID_MISSION_MOTIVE')->nullable();
            $table->string('SELECTION_ECO_A_FACTURER')->nullable();
            $table->string('SELECTION_ECO_VALIDEE')->nullable();
            $table->string('SELECTION_FACTURATION')->nullable();
            $table->string('SOUS_MOTIF_1', 50)->nullable();
            $table->string('SOUS_MOTIF_1_FROM_MONTH')->nullable();
            $table->string('SOUS_MOTIF_1_FROM_YEAR')->nullable();
            $table->string('SOUS_MOTIF_1_TO_MONTH')->nullable();
            $table->string('SOUS_MOTIF_1_TO_YEAR')->nullable();
            $table->string('SOUS_MOTIF_2')->nullable();
            $table->date('SYS_DATE_CREATION')->nullable();
            $table->date('SYS_DATE_MODIFICATION')->nullable();
            $table->time('SYS_HEURE_CREATION')->nullable();
            $table->time('SYS_HEURE_MODIFICATION')->nullable();
            $table->string('SYS_USER_CREATION')->nullable();
            $table->string('SYS_USER_MODIFICATION')->nullable();
            $table->string('TMP_NO_INVOICE')->nullable();
            $table->string('YEAR')->nullable();
            $table->string('CRITICITY')->nullable();
            $table->string('TIME')->nullable();

            $table->unsignedBigInteger('mission_motive_id')->nullable();
            $table->foreign('mission_motive_id')->references('id')->on('mission_motives')->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mission_motive_ecos');
    }
}
