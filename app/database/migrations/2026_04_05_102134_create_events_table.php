<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('events', function (Blueprint $table) {
        $table->increments('id'); // イベントid 
        $table->integer('user_id'); // ユーザid 
        $table->integer('capacity'); // 定員数 
        $table->string('title', 255); // タイトル 
        $table->string('image', 255)->nullable(); // イメージ画像 
        $table->string('comment', 255)->nullable(); // 紹介文 
        $table->date('date'); // 日程 
        $table->tinyInteger('format')->default(0); // 0=zoom/1=YouTube 
        $table->tinyInteger('type')->default(0); // 0=セミナー/1=勉強会... 
        $table->tinyInteger('is_visible')->default(0); // 論理削除 
        $table->timestamps(); // 作成日・更新日時 
    });
}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
