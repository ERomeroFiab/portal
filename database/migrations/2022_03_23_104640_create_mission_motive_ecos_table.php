<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionMotiveEcosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mission_motive_ecos', function (Blueprint $table) {
            $table->id('id');
            $table->date('DATE_PREVISIONNELLE')->nullable();
            $table->double('ECO_ABANDONNEE')->nullable();
            $table->double('ECO_A_FACTURER')->nullable();
            $table->double('ECO_ECART')->nullable();
            $table->double('ECO_PRESENTEE')->nullable();
            $table->double('ECO_VALIDEE')->nullable();
            $table->string('ID_MISSION_MOTIVE_ECO', 32)->nullable();
            $table->binary('NOTES')->nullable();
            $table->double('PACKAGE')->nullable();
            $table->string('PID_MISSION_MOTIVE', 32)->nullable();
            $table->char('SELECTION_ECO_A_FACTURER', 1)->nullable();
            $table->char('SELECTION_ECO_VALIDEE', 1)->nullable();
            $table->char('SELECTION_FACTURATION', 1)->nullable();
            $table->string('SOUS_MOTIF_1', 50)->nullable();
            $table->string('SOUS_MOTIF_1_FROM_MONTH', 2)->nullable();
            $table->string('SOUS_MOTIF_1_FROM_YEAR', 4)->nullable();
            $table->string('SOUS_MOTIF_1_TO_MONTH', 2)->nullable();
            $table->string('SOUS_MOTIF_1_TO_YEAR', 4)->nullable();
            $table->string('SOUS_MOTIF_2', 50)->nullable();
            $table->date('SYS_DATE_CREATION')->nullable();
            $table->date('SYS_DATE_MODIFICATION')->nullable();
            $table->time('SYS_HEURE_CREATION')->nullable();
            $table->time('SYS_HEURE_MODIFICATION')->nullable();
            $table->string('SYS_USER_CREATION', 20)->nullable();
            $table->string('SYS_USER_MODIFICATION', 20)->nullable();
            $table->string('TMP_NO_INVOICE', 10)->nullable();
            $table->string('YEAR', 10)->nullable();
            $table->string('CRITICITY', 1)->nullable();
            $table->string('TIME', 1)->nullable();
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
        Schema::dropIfExists('mission_motive_ecos');
    }
}
