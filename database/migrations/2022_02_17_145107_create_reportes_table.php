<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportesTable extends Migration
{
    public function up()
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('gestor_id')->nullable();
            $table->foreign('gestor_id')->references('id')->on('users')->onDelete('set null');

            $table->unsignedBigInteger('gestion_id')->nullable();
            $table->foreign('gestion_id')->references('id')->on('gestions')->onDelete('set null');

            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('status')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reportes');
    }
}
