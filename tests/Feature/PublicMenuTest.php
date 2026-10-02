<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\CafeMenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_menu_displays_active_products_and_hides_inactive_products(): void
    {
        Product::create([
            'name' => 'Kopi Susu',
            'category' => 'Minuman',
            'description' => 'Kopi susu khas',
            'price' => 18000,
            'stock' => 5,
            'is_active' => true,
        ]);
        Product::create([
            'name' => 'Menu Rahasia',
            'category' => 'Makanan',
            'price' => 25000,
            'stock' => 2,
            'is_active' => false,
        ]);

        $this->get(route('menu.index'))
            ->assertOk()
            ->assertSee('Kopi Susu')
            ->assertSee('Rp 18.000')
            ->assertDontSee('Menu Rahasia');
    }

    public function test_homepage_menu_link_points_to_public_catalog(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('menu.index'));
    }

    public function test_public_menu_shows_the_correct_credited_image_for_ayam_geprek(): void
    {
        Product::create([
            'name' => 'Nasi ayam geprek',
            'category' => 'Makanan',
            'description' => 'Ayam geprek dengan nasi.',
            'price' => 25000,
            'stock' => 5,
            'image' => 'images/cafe-menu/nasi-ayam-geprek.jpg',
            'is_active' => true,
        ]);

        $this->get(route('menu.index'))
            ->assertOk()
            ->assertSee('src="'.asset('images/cafe-menu/nasi-ayam-geprek.jpg').'" alt="Nasi ayam geprek" loading="lazy"', false)
            ->assertSee('Foto: Supardisahabu · CC BY-SA 4.0')
            ->assertSee('https://commons.wikimedia.org/wiki/File:Ayam_Geprek_Uyat.jpg');

        $this->assertFileExists(public_path('images/cafe-menu/nasi-ayam-geprek.jpg'));
    }

    public function test_cafe_menu_seeder_adds_items_once_without_overwriting_existing_products(): void
    {
        $existing = Product::create([
            'code' => 'CFK-KOP-001',
            'name' => 'Nama menu yang sudah ada',
            'category' => 'Kopi',
            'price' => 19000,
            'stock' => 3,
            'is_active' => false,
            'image' => 'products/custom-menu-image.jpg',
        ]);
        $existingSeededImage = Product::create([
            'code' => 'CFK-ADD-004',
            'name' => 'Whipped Cream',
            'category' => 'Add-on',
            'price' => 5000,
            'stock' => 20,
            'is_active' => true,
            'image' => 'images/cafe-menu/americano.jpg',
        ]);

        $seeder = new CafeMenuSeeder;
        $seeder->run();
        $seeder->run();

        $this->assertSame(1, Product::where('code', 'CFK-KOP-001')->count());
        $this->assertSame('Nama menu yang sudah ada', $existing->fresh()->name);
        $this->assertFalse($existing->fresh()->is_active);
        $this->assertSame('products/custom-menu-image.jpg', $existing->fresh()->image);
        $this->assertSame(
            'images/cafe-menu/whipped-cream.jpg',
            $existingSeededImage->fresh()->image
        );
        $this->assertSame(26, Product::where('code', 'like', 'CFK-%')->count());
        $this->assertSame(
            'images/cafe-menu/americano.jpg',
            Product::where('code', 'CFK-KOP-002')->value('image')
        );

    }

    public function test_cafe_menu_seeder_assigns_matching_images_to_each_menu(): void
    {
        (new CafeMenuSeeder)->run();

        $expectedImages = [
            'CFK-KOP-001' => 'images/cafe-menu/espresso.jpg',
            'CFK-KOP-002' => 'images/cafe-menu/americano.jpg',
            'CFK-KOP-003' => 'images/cafe-menu/cappuccino.jpg',
            'CFK-KOP-004' => 'images/cafe-menu/latte.jpg',
            'CFK-KOP-005' => 'images/cafe-menu/latte.jpg',
            'CFK-KOP-006' => 'images/cafe-menu/caramel-macchiato.jpg',
            'CFK-KOP-007' => 'images/cafe-menu/iced-coffee.jpg',
            'CFK-KOP-008' => 'images/cafe-menu/v60.jpg',
            'CFK-KOP-009' => 'images/cafe-menu/mocha.jpg',
            'CFK-NK-001' => 'images/cafe-menu/matcha.jpg',
            'CFK-NK-002' => 'images/cafe-menu/chocolate.jpg',
            'CFK-NK-003' => 'images/cafe-menu/lemon-tea.jpg',
            'CFK-NK-004' => 'images/cafe-menu/thai-tea.jpg',
            'CFK-NK-005' => 'images/cafe-menu/strawberry-milkshake.jpg',
            'CFK-NK-006' => 'images/cafe-menu/taro-milk-tea.jpg',
            'CFK-MKN-001' => 'images/cafe-menu/nasi-goreng.jpg',
            'CFK-MKN-002' => 'images/cafe-menu/mie-goreng.jpg',
            'CFK-MKN-003' => 'images/cafe-menu/sate-ayam.jpg',
            'CFK-SNK-001' => 'images/cafe-menu/croissant.jpg',
            'CFK-SNK-002' => 'images/cafe-menu/fries.jpg',
            'CFK-SNK-003' => 'images/cafe-menu/toast.jpg',
            'CFK-SNK-004' => 'images/cafe-menu/cheesecake.jpg',
            'CFK-ADD-001' => 'images/cafe-menu/extra-espresso-shot.jpg',
            'CFK-ADD-002' => 'images/cafe-menu/oat-milk.jpg',
            'CFK-ADD-003' => 'images/cafe-menu/caramel-syrup.jpg',
            'CFK-ADD-004' => 'images/cafe-menu/whipped-cream.jpg',
        ];

        $response = $this->get(route('menu.index'))->assertOk();

        foreach ($expectedImages as $code => $image) {
            $product = Product::where('code', $code)->firstOrFail();

            $this->assertSame($image, $product->image);
            $this->assertFileExists(public_path($image));
            $response->assertSee(
                'src="'.asset($image).'" alt="'.$product->name.'" loading="lazy"',
                false
            );
        }

        $response
            ->assertSee('Foto: Aneth David (SLU) · CC BY-SA 4.0')
            ->assertSee('Foto: jeffreyw · CC BY 2.0')
            ->assertSee('Foto: Mina Başer · Pexels')
            ->assertSee('Foto: Polina Tankilevitch · Pexels')
            ->assertSee('Foto: MART PRODUCTION · Pexels')
            ->assertSee('Foto: RDNE Stock project · Pexels')
            ->assertSee('Foto: Monakha laden · Pexels')
            ->assertSee('Foto: Ega Morgan · Pexels')
            ->assertSee('Foto: Man Fong Wong · Pexels')
            ->assertSee('Foto: Nguyễn Tiến Thịnh · Pexels')
            ->assertSee('Foto: Arief Setiawan · Pexels')
            ->assertSee('Foto: bams awey · Pexels')
            ->assertSee('Thai Tea')
            ->assertSee('Strawberry Milkshake')
            ->assertSee('Taro Milk Tea')
            ->assertSee('Nasi Goreng Spesial')
            ->assertSee('Mie Goreng Jawa')
            ->assertSee('Sate Ayam');
    }
}
