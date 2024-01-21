<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::insert([
            [
                'name' => 'Marketing Basic',
                'description' => 'Paquéte básico de marketing digital',
                'status' => 'active',
                'price' => 2784.00,
                'unity' => 'meses',
                'slug' => 'marketing-basic',
                'manage_stock' => 0,
                'tax_amount' => 16,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                'name' => 'Marketing Plus',
                'description' => 'Paquéte plus de marketing digital',
                'status' => 'active',
                'price' => 8150.50,
                'unity' => 'meses',
                'slug' => 'marketing-plus',
                'manage_stock' => 0,
                'tax_amount' => 16,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                'name' => 'Marketing Corporative',
                'description' => 'Paquéte corporative de marketing digital',
                'status' => 'active',
                'price' => 18110.00,
                'unity' => 'meses',
                'slug' => 'marketing-corporative',
                'manage_stock' => 0,
                'tax_amount' => 16,
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ]);
    }
}
