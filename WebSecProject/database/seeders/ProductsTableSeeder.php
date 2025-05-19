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
                'photo' => 'images/laptop.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Smartphone',
                'price' => 69900, // $699.00
                'stock' => 20,
                'description' => 'A sleek smartphone with a great camera.',
                'photo' => 'images/smartphone.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Headphones',
                'price' => 9900, // $99.00
                'stock' => 50,
                'description' => 'Noise-canceling headphones with long battery life.',
                'photo' => 'images/headphones.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '4K Smart TV',
                'price' => 129900, // $1,299.00
                'stock' => 15,
                'description' => 'Ultra HD 4K Smart TV with voice control and streaming apps built-in.',
                'photo' => 'images/smart_tv.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tablet Pro',
                'price' => 54900, // $549.00
                'stock' => 25,
                'description' => 'High-performance tablet with a stunning display and all-day battery life.',
                'photo' => 'images/tablet.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Wireless Earbuds',
                'price' => 12900, // $129.00
                'stock' => 40,
                'description' => 'True wireless earbuds with active noise cancellation and sweat resistance.',
                'photo' => 'images/earbuds.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gaming Console',
                'price' => 49900, // $499.00
                'stock' => 8,
                'description' => 'Next-generation gaming console with 4K gaming, ray tracing and ultra-fast loading.',
                'photo' => 'images/console.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Smartwatch',
                'price' => 24900, // $249.00
                'stock' => 30,
                'description' => 'Advanced smartwatch with health monitoring, GPS, and smartphone notifications.',
                'photo' => 'images/smartwatch.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Digital Camera',
                'price' => 79900, // $799.00
                'stock' => 12,
                'description' => 'Professional-grade digital camera with 24MP sensor and 4K video recording.',
                'photo' => 'images/camera.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bluetooth Speaker',
                'price' => 7900, // $79.00
                'stock' => 35,
                'description' => 'Portable Bluetooth speaker with 360° sound and 20-hour battery life.',
                'photo' => 'images/speaker.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ultra-Wide Monitor',
                'price' => 34900, // $349.00
                'stock' => 18,
                'description' => '34-inch curved ultra-wide monitor ideal for gaming and productivity.',
                'photo' => 'images/monitor.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mechanical Keyboard',
                'price' => 12900, // $129.00
                'stock' => 22,
                'description' => 'RGB mechanical keyboard with customizable keys and tactile feedback.',
                'photo' => 'images/keyboard.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'External SSD',
                'price' => 14900, // $149.00
                'stock' => 45,
                'description' => '1TB external SSD with USB-C connectivity and ultra-fast data transfer speeds.',
                'photo' => 'images/ssd.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}