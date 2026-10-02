<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class CafeMenuSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'code' => 'CFK-KOP-001',
                'name' => 'Espresso',
                'category' => 'Kopi',
                'description' => 'Espresso pekat dengan aroma biji kopi pilihan.',
                'image' => 'images/cafe-menu/espresso.jpg',
                'price' => 18000,
                'stock' => 30,
            ],
            [
                'code' => 'CFK-KOP-002',
                'name' => 'Americano',
                'category' => 'Kopi',
                'description' => 'Espresso dan air panas dengan rasa kopi yang ringan.',
                'image' => 'images/cafe-menu/americano.jpg',
                'price' => 22000,
                'stock' => 30,
            ],
            [
                'code' => 'CFK-KOP-003',
                'name' => 'Cappuccino',
                'category' => 'Kopi',
                'description' => 'Perpaduan espresso, susu steamed, dan foam lembut.',
                'image' => 'images/cafe-menu/cappuccino.jpg',
                'price' => 28000,
                'stock' => 25,
            ],
            [
                'code' => 'CFK-KOP-004',
                'name' => 'Cafe Latte',
                'category' => 'Kopi',
                'description' => 'Espresso dengan susu creamy dan foam tipis.',
                'image' => 'images/cafe-menu/latte.jpg',
                'price' => 28000,
                'stock' => 25,
            ],
            [
                'code' => 'CFK-KOP-005',
                'name' => 'Vanilla Latte',
                'category' => 'Kopi',
                'description' => 'Cafe latte lembut dengan sentuhan sirup vanilla.',
                'image' => 'images/cafe-menu/latte.jpg',
                'price' => 32000,
                'stock' => 20,
            ],
            [
                'code' => 'CFK-KOP-006',
                'name' => 'Caramel Macchiato',
                'category' => 'Kopi',
                'description' => 'Espresso, susu, dan saus karamel yang manis.',
                'image' => 'images/cafe-menu/caramel-macchiato.jpg',
                'price' => 33000,
                'stock' => 20,
            ],
            [
                'code' => 'CFK-KOP-007',
                'name' => 'Kopi Susu Gula Aren',
                'category' => 'Kopi',
                'description' => 'Kopi susu dingin dengan manis legit gula aren.',
                'image' => 'images/cafe-menu/iced-coffee.jpg',
                'price' => 25000,
                'stock' => 25,
            ],
            [
                'code' => 'CFK-KOP-008',
                'name' => 'V60 Manual Brew',
                'category' => 'Kopi',
                'description' => 'Kopi seduh manual dengan karakter biji kopi pilihan.',
                'image' => 'images/cafe-menu/v60.jpg',
                'price' => 30000,
                'stock' => 15,
            ],
            [
                'code' => 'CFK-KOP-009',
                'name' => 'Cafe Mocha',
                'category' => 'Kopi',
                'description' => 'Espresso dan susu dengan cokelat yang kaya rasa.',
                'image' => 'images/cafe-menu/mocha.jpg',
                'price' => 32000,
                'stock' => 20,
            ],
            [
                'code' => 'CFK-NK-001',
                'name' => 'Matcha Latte',
                'category' => 'Non-Kopi',
                'description' => 'Matcha pilihan dengan susu lembut, tersedia panas atau dingin.',
                'image' => 'images/cafe-menu/matcha.jpg',
                'price' => 28000,
                'stock' => 20,
            ],
            [
                'code' => 'CFK-NK-002',
                'name' => 'Chocolate',
                'category' => 'Non-Kopi',
                'description' => 'Minuman cokelat creamy untuk dinikmati panas atau dingin.',
                'image' => 'images/cafe-menu/chocolate.jpg',
                'price' => 26000,
                'stock' => 20,
            ],
            [
                'code' => 'CFK-NK-003',
                'name' => 'Lemon Tea',
                'category' => 'Non-Kopi',
                'description' => 'Teh segar dengan lemon, cocok dinikmati dingin.',
                'image' => 'images/cafe-menu/lemon-tea.jpg',
                'price' => 18000,
                'stock' => 20,
            ],
            [
                'code' => 'CFK-NK-004',
                'name' => 'Thai Tea',
                'category' => 'minuman',
                'description' => 'Teh Thailand creamy dengan rasa teh yang khas, disajikan dingin.',
                'image' => 'images/cafe-menu/thai-tea.jpg',
                'price' => 22000,
                'stock' => 20,
            ],
            [
                'code' => 'CFK-NK-005',
                'name' => 'Strawberry Milkshake',
                'category' => 'minuman',
                'description' => 'Milkshake stroberi lembut dengan rasa buah yang segar.',
                'image' => 'images/cafe-menu/strawberry-milkshake.jpg',
                'price' => 25000,
                'stock' => 20,
            ],
            [
                'code' => 'CFK-NK-006',
                'name' => 'Taro Milk Tea',
                'category' => 'minuman',
                'description' => 'Milk tea taro yang creamy dengan rasa manis lembut.',
                'image' => 'images/cafe-menu/taro-milk-tea.jpg',
                'price' => 25000,
                'stock' => 20,
            ],
            [
                'code' => 'CFK-MKN-001',
                'name' => 'Nasi Goreng Spesial',
                'category' => 'makanan',
                'description' => 'Nasi goreng berbumbu rempah dengan telur mata sapi dan pelengkap segar.',
                'image' => 'images/cafe-menu/nasi-goreng.jpg',
                'price' => 23000,
                'stock' => 20,
            ],
            [
                'code' => 'CFK-MKN-002',
                'name' => 'Mie Goreng Jawa',
                'category' => 'makanan',
                'description' => 'Mie goreng gurih dengan ayam, sayuran segar, dan sate ayam.',
                'image' => 'images/cafe-menu/mie-goreng.jpg',
                'price' => 22000,
                'stock' => 20,
            ],
            [
                'code' => 'CFK-MKN-003',
                'name' => 'Sate Ayam',
                'category' => 'makanan',
                'description' => 'Sate ayam bakar berbumbu dengan aroma smoky dan rasa gurih manis.',
                'image' => 'images/cafe-menu/sate-ayam.jpg',
                'price' => 25000,
                'stock' => 20,
            ],
            [
                'code' => 'CFK-SNK-001',
                'name' => 'Butter Croissant',
                'category' => 'Snack',
                'description' => 'Pastry berlapis yang renyah di luar dan lembut di dalam.',
                'image' => 'images/cafe-menu/croissant.jpg',
                'price' => 22000,
                'stock' => 12,
            ],
            [
                'code' => 'CFK-SNK-002',
                'name' => 'French Fries',
                'category' => 'Snack',
                'description' => 'Kentang goreng renyah dengan bumbu gurih.',
                'image' => 'images/cafe-menu/fries.jpg',
                'price' => 20000,
                'stock' => 15,
            ],
            [
                'code' => 'CFK-SNK-003',
                'name' => 'Roti Bakar Cokelat Keju',
                'category' => 'Snack',
                'description' => 'Roti panggang hangat dengan cokelat dan keju.',
                'image' => 'images/cafe-menu/toast.jpg',
                'price' => 22000,
                'stock' => 12,
            ],
            [
                'code' => 'CFK-SNK-004',
                'name' => 'Cheesecake',
                'category' => 'Snack',
                'description' => 'Potongan cheesecake lembut untuk teman minum kopi.',
                'image' => 'images/cafe-menu/cheesecake.jpg',
                'price' => 28000,
                'stock' => 10,
            ],
            [
                'code' => 'CFK-ADD-001',
                'name' => 'Extra Espresso Shot',
                'category' => 'Add-on',
                'description' => 'Tambahan satu shot espresso untuk minuman favorit Anda.',
                'image' => 'images/cafe-menu/extra-espresso-shot.jpg',
                'price' => 8000,
                'stock' => 30,
            ],
            [
                'code' => 'CFK-ADD-002',
                'name' => 'Oat Milk',
                'category' => 'Add-on',
                'description' => 'Pilihan pengganti susu dengan tekstur lembut.',
                'image' => 'images/cafe-menu/oat-milk.jpg',
                'price' => 8000,
                'stock' => 15,
            ],
            [
                'code' => 'CFK-ADD-003',
                'name' => 'Caramel Syrup',
                'category' => 'Add-on',
                'description' => 'Tambahan sirup karamel untuk sentuhan manis.',
                'image' => 'images/cafe-menu/caramel-syrup.jpg',
                'price' => 5000,
                'stock' => 20,
            ],
            [
                'code' => 'CFK-ADD-004',
                'name' => 'Whipped Cream',
                'category' => 'Add-on',
                'description' => 'Topping krim lembut untuk minuman dan dessert.',
                'image' => 'images/cafe-menu/whipped-cream.jpg',
                'price' => 5000,
                'stock' => 20,
            ],
        ];

        foreach ($products as $product) {
            $record = Product::firstOrCreate(
                ['code' => $product['code']],
                $product + ['is_active' => true]
            );

            if (
                ! $record->image
                || str_starts_with($record->image, 'products/cafe-menu/')
                || str_starts_with($record->image, 'images/cafe-menu/')
            ) {
                $record->update(['image' => $product['image']]);
            }
        }
    }
}
