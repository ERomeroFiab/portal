<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewIdColumnToGestionsTable extends Migration
{
    public function up()
    {
        Schema::table('gestions', function (Blueprint $table) {
            $table->string('ID_MISSION_MOTIVE_ECO')->nullable()->after('id')->comment('id in silvertool');
        });
    }

    public function down()
    {
        Schema::table('gestions', function (Blueprint $table) {
            $table->dropColumn('ID_MISSION_MOTIVE_ECO');
        });
    }
}
