<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEventsTableForRequirements extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['date', 'format', 'capacity']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dateTime('date')->after('comment'); 
            $table->string('format')->after('date');    
            $table->integer('capacity')->nullable()->after('format'); 
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['date', 'format', 'capacity']);
        });
        Schema::table('events', function (Blueprint $table) {
            $table->date('date')->after('comment');
            $table->tinyInteger('format')->after('date');
            $table->integer('capacity')->after('format');
        });
    }
}