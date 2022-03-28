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

            $table->string('COMMENTS_SITE', 50)->nullable();
            $table->string('CONSULTANT', 20)->nullable();
            $table->date('DATE_LIMITE')->nullable();
            $table->string('ETAPE_COURANTE', 50)->nullable();
            $table->string('ID_MISSION_MOTIVE', 32)->nullable();
            $table->string('MOTIF', 70)->nullable();
            $table->string('PID_MISSION', 32)->nullable();
            $table->double('POURCENTAGE')->nullable();
            $table->date('SYS_DATE_CREATION')->nullable();
            $table->date('SYS_DATE_MODIFICATION')->nullable();
            $table->time('SYS_HEURE_CREATION')->nullable();
            $table->time('SYS_HEURE_MODIFICATION')->nullable();
            $table->string('SYS_USER_CREATION', 20)->nullable();
            $table->string('SYS_USER_MODIFICATION', 20)->nullable();

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
