<?php

use Illuminate\Database\Seeder;
use App\Models\User; // もし User モデルが App\Models にある場合
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
public function run()
{
    // ID: 1 参加者 Aさん
    User::create([
        'id' => 1,
        'name' => '参加者A',
        'email' => 'userA@example.com',
        'password' => Hash::make('password123'),
    ]);

    // ID: 2 主催者 Bさん
    User::create([
        'id' => 2,
        'name' => '主催者B',
        'email' => 'userB@example.com',
        'password' => Hash::make('password123'),
    ]);
}}