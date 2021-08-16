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
        $prospects = Board::where('slug', 'prospects')->first();
        $lose = Board::where('slug', 'lose')->first();

        Column::insert([
            [
                'board_id' => $prospects->id,
                'name' => 'Bandeja de entrada',
                'slug' => 'inbox',
                'text_color' => '#fff',
                'bg_color' => '#c54141',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'board_id' => $prospects->id,
                'name' => 'Por contactar',
                'slug' => 'to-contact',
                'text_color' => '#fff',
                'bg_color' => '#bf7e47',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'board_id' => $prospects->id,
                'name' => 'Citados',
                'slug' => 'cited',
                'text_color' => '#fff',
                'bg_color' => '#d3ba47',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'board_id' => $prospects->id,
                'name' => 'Por cerrar',
                'slug' => 'to-close',
                'text_color' => '#fff',
                'bg_color' => '#97c73e',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'board_id' => $prospects->id,
                'name' => 'Cerrados',
                'slug' => 'closures',
                'text_color' => '#fff',
                'bg_color' => '#3cac4e',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'board_id' => $lose->id,
                'name' => 'Perdidos',
                'slug' => 'lost',
                'text_color' => '#fff',
                'bg_color' => '#c54141',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
