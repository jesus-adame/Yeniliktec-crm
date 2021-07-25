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
                'name' => 'prospects',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'tracing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'lose',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
