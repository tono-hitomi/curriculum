<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('reports', function (Blueprint $table) {
        $table->increments('lid'); // レポートid
        $table->integer('user_id'); // ユーザid
        $table->integer('event_id'); // イベントid
        $table->string('title', 255); // タイトル
        $table->text('content'); // 内容 (型はtextが望ましいです)
        $table->string('image', 255)->nullable(); // 画像
        $table->tinyInteger('is_visible')->default(0); // 論理削除
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
