<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // 標準の 'id' (PK) に戻します
            $table->string('name', 20); // 名前
            $table->string('email', 50)->unique(); // メールアドレス
            $table->string('password', 100); // パスワード
            $table->string('image')->nullable(); // プロフィール画像
            $table->string('comment', 255)->nullable(); // 自己紹介文
            $table->tinyInteger('roll')->default(0); // ユーザー区別 (一般=0/管理者=1)
            $table->softDeletes(); // 'is_active' の代わりに標準の 'deleted_at' を使います
            $table->string('password_token')->nullable(); // パスワードトークン
            $table->timestamps(); // created_at と updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}