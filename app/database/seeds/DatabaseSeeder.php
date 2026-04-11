<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
public function run()
{
    // 他のシーダーを呼び出す
    $this->call([
        UserSeeder::class,
        EventSeeder::class,
    ]);
}}
