<?php

namespace Database\Seeders;

use App\Models\Board;
use Illuminate\Database\Seeder;

class BoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Board::insert([
            [
                'name' => 'Prospectos',
                'slug' => 'prospects',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Seguimiento',
                'slug' => 'tracing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Perdidos',
                'slug' => 'lose',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
