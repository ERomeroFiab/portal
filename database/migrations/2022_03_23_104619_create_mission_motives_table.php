<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionMotivesTable extends Migration
{
    public function up()
    {
        Schema::create('mission_motives', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('COMMENTS_SITE')->nullable();
            $table->string('CONSULTANT')->nullable();
            $table->dateTime('DATE_LIMITE')->nullable();
            $table->string('ETAPE_COURANTE')->nullable();
            $table->string('ID_MISSION_MOTIVE')->nullable();
            $table->string('MOTIF')->nullable();
            $table->string('PID_MISSION')->nullable();
            $table->double('POURCENTAGE')->nullable();
            $table->dateTime('SYS_DATE_CREATION')->nullable();
            $table->dateTime('SYS_DATE_MODIFICATION')->nullable();
            $table->time('SYS_HEURE_CREATION')->nullable();
            $table->time('SYS_HEURE_MODIFICATION')->nullable();
            $table->string('SYS_USER_CREATION')->nullable();
            $table->string('SYS_USER_MODIFICATION')->nullable();

            $table->unsignedBigInteger('mission_id')->nullable();
            $table->foreign('mission_id')->references('id')->on('missions')->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mission_motives');
    }
}
