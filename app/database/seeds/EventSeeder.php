<?php

use Illuminate\Database\Seeder;
// 冒頭の use App\Models\Event; はあってもなくても大丈夫です

class EventSeeder extends Seeder
{
    public function run()
    {
        
        \App\Models\Event::create([
            'user_id' => 2,
            'title' => 'B主催のイベント',
            'comment' => 'Bさんが企画したイベントです。',
            'capacity' => 10,
            'date' => '2026-05-01',
            'format' => 0,
            'type' => 1,
        ]);
    }
}