<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Laptop',
                'price' => 99900, // $999.00 (stored as cents)
                'stock' => 10,
                'description' => 'A high-performance laptop for gaming and work.',
                'photo' => 'products/laptop.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Smartphone',
                'price' => 69900, // $699.00
                'stock' => 20,
                'description' => 'A sleek smartphone with a great camera.',
                'photo' => 'products/smartphone.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Headphones',
                'price' => 9900, // $99.00
                'stock' => 50,
                'description' => 'Noise-canceling headphones with long battery life.',
                'photo' => 'products/headphones.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}