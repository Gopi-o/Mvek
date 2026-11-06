<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Database\Seeders\ShopSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ShopSeeder::class);
    }

    public function test_all_customer_pages_render(): void
    {
        foreach (['/', '/about', '/catalog', '/promotions', '/new', '/brands', '/reviews', '/contacts', '/cart', '/login', '/register'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_catalog_filters_products_by_category(): void
    {
        $this->get('/catalog?category=dogs')
            ->assertOk()
            ->assertSee('Полнорационный корм с индейкой')
            ->assertDontSee('Игрушка-мышка с кошачьей мятой');
    }

    public function test_guest_can_create_order_from_cart(): void
    {
        $product = Product::where('slug', 'dog-food-turkey')->firstOrFail();

        $this->post("/cart/{$product->id}")
            ->assertRedirect();
        $this->get('/checkout')->assertOk();
        $response = $this->post('/checkout', [
            'customer_name' => 'Иван Петров',
            'email' => 'ivan@example.com',
            'phone' => '+79990000000',
            'address' => 'Москва, ул. Примерная, 1',
        ]);
        $response->assertRedirect('/');

        $this->assertDatabaseHas('orders', ['email' => 'ivan@example.com', 'total' => '1590.00']);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 1]);
        $this->assertSame(23, $product->fresh()->stock);
        $this->get('/cart')->assertSee('Корзина пока пустая');
    }

    public function test_registered_customer_can_update_profile_and_see_account(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/account')->assertOk();
        $this->actingAs($user)->put('/account', [
            'name' => 'Новое имя',
            'email' => $user->email,
        ])->assertRedirect('/account');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Новое имя']);
    }
}
