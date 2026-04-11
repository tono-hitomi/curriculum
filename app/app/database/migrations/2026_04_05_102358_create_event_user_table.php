<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('event_user', function (Blueprint $table) {
        $table->increments('lid'); // イベントユーザーid
        $table->integer('event_id'); // イベントid
        $table->integer('user_id'); // ユーザーid
        $table->string('comment', 255)->nullable(); // 申込コメント
        $table->tinyInteger('step')->default(0); // 申請前=0/申請済み=1/承認=2
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
        Schema::dropIfExists('event_user');
    }
}
