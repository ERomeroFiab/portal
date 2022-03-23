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
            $table->string('COORDINATOR')->nullable();
            $table->string('CURRENT_STEP')->nullable();
            $table->dateTime('DATE_DEBUT')->nullable();
            $table->dateTime('DATE_DEBUT_ANALYSE')->nullable();
            $table->dateTime('DATE_FIN_ANALYSE')->nullable();
            $table->dateTime('DATE_FIN_MISSION')->nullable();
            $table->dateTime('DEADLINE')->nullable();
            $table->string('NO_CONTRAT')->nullable();
            $table->string('NO_MISSION')->nullable();
            $table->double('POURCENTAGE')->nullable();
            $table->string('PRIORITY')->nullable();
            $table->string('PRODUIT')->nullable();
            $table->string('PROJECT_MANAGER')->nullable();

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
