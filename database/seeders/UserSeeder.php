<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::create([
            'name' => 'root',
            'email' => 'root@yeniliktec.com',
            'password' => bcrypt(env('ROOT_PASSWORD', 'secret22')),
            'email_verified_at' => now(),
        ]);

        User::insert([
            [
                'name' => 'Ruben Adame',
                'email' => 'ruben.adame@yeniliktec.com',
                'password' => bcrypt('secret22'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jesús Adame',
                'email' => 'jesus.adame@yeniliktec.com',
                'password' => bcrypt('secret22'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ventas Yeniliktec',
                'email' => 'ventas@yeniliktec.com',
                'password' => bcrypt('secret22'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $superAdmin->assignRole('super admin');
    }
}
