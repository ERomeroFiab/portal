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
            $table->string('COORDINATOR');
            $table->string('CURRENT_STEP');
            $table->dateTime('DATE_DEBUT');
            $table->dateTime('DATE_DEBUT_ANALYSE');
            $table->dateTime('DATE_FIN_ANALYSE');
            $table->dateTime('DATE_FIN_MISSION');
            $table->dateTime('DEADLINE');
            $table->string('NO_CONTRAT');
            $table->string('NO_MISSION');
            $table->double('POURCENTAGE');
            $table->string('PRIORITY');
            $table->string('PRODUIT');
            $table->string('PROJECT_MANAGER');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('missions');
    }
}
