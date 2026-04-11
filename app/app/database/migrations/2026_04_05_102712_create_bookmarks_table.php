<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookmarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('bookmarks', function (Blueprint $table) {
        $table->increments('lid'); // ブックマークid
        $table->integer('user_id'); // ユーザid
        $table->integer('event_id'); // イベントid
        $table->timestamps(); // 作成日時・更新日時
    });
}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookmarks');
    }
}
