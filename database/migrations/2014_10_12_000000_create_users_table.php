<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('set null');

            $table->string('name');
            $table->string('email')->nullable();
            $table->string('rut')->unique()->comment('Head Office');
            $table->string('rol')->comment('Gestor ó Cliente.');
            $table->string('password');
            $table->dateTime('first_login')->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
