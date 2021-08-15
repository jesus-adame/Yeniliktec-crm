<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Column;
use Illuminate\Database\Seeder;

class ColumnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prospects = Board::where('name', 'prospects')->first();
        $tracing = Board::where('name', 'tracing')->first();
        $lose = Board::where('name', 'lose')->first();

        Column::insert([
            [
                'board_id' => $prospects->id,
                'name' => 'inbox',
                'text_color' => '#fff',
                'bg_color' => '#A80000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'board_id' => $prospects->id,
                'name' => 'to-contact',
                'text_color' => '#fff',
                'bg_color' => '#C9A708',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'board_id' => $prospects->id,
                'name' => 'cited',
                'text_color' => '#fff',
                'bg_color' => '#C9A708',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'board_id' => $prospects->id,
                'name' => 'to-close',
                'text_color' => '#fff',
                'bg_color' => '#C9A708',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'board_id' => $prospects->id,
                'name' => 'closures',
                'text_color' => '#fff',
                'bg_color' => '#03A51D',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'board_id' => $lose->id,
                'name' => 'lost',
                'text_color' => '#fff',
                'bg_color' => '#A80000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
