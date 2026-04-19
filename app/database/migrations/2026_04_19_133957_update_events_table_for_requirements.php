<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEventsTableForRequirements extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            // 一旦既存の古い設定のカラムを削除
            $table->dropColumn(['date', 'format', 'capacity']);
        });

        Schema::table('events', function (Blueprint $table) {
            // 要件に合わせた型で作り直し
            $table->dateTime('date')->after('comment'); // 開催日時（datetime）
            $table->string('format')->after('date');    // イベント形式（string）
            $table->integer('capacity')->nullable()->after('format'); // 定員数（任意なのでnullable）
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