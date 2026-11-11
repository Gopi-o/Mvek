<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Database\Seeders\ShopSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ShopSeeder::class);
    }

    public function test_admin_routes_are_protected_by_role(): void
    {
        $this->get('/admin')->assertRedirect('/login');

        $customer = User::factory()->create(['role' => 'user']);
        $this->actingAs($customer)->get('/admin')->assertForbidden();

        $manager = User::factory()->create(['role' => 'manager']);
        $this->actingAs($manager)->get('/admin')->assertOk();
        $this->get('/admin/orders')->assertOk();
        $this->get('/admin/reviews')->assertOk();
        $this->get('/admin/products')->assertForbidden();
        $this->get('/admin/users')->assertForbidden();
    }

    public function test_admin_can_render_management_pages(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        foreach ([
            '/admin',
            '/admin/products',
            '/admin/products/create',
            '/admin/categories',
            '/admin/categories/create',
            '/admin/brands',
            '/admin/brands/create',
            '/admin/orders',
            '/admin/reviews',
            '/admin/users',
        ] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_admin_can_manage_products_and_update_order_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $category = Category::firstOrFail();
        $brand = Brand::firstOrFail();

        $this->post('/admin/products', [
            'name' => 'Новый корм',
            'slug' => 'new-food',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'description' => 'Корм для домашних животных',
            'price' => 1200,
            'sale_price' => 1000,
            'stock' => 5,
            'is_new' => 1,
        ])->assertRedirect('/admin/products');

        $product = Product::where('slug', 'new-food')->firstOrFail();
        $this->assertTrue($product->is_new);

        $order = Order::create([
            'customer_name' => 'Покупатель',
            'email' => 'buyer@example.com',
            'phone' => '+79990000000',
            'address' => 'Москва',
            'total' => 1000,
        ]);

        $this->patch("/admin/orders/{$order->id}", ['status' => 'processing'])
            ->assertRedirect();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'processing']);
    }

    public function test_only_admin_can_moderate_reviews_and_assign_roles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'manager']);
        $review = Review::firstOrFail();

        $this->actingAs($manager)
            ->patch("/admin/reviews/{$review->id}", ['is_approved' => 1])
            ->assertForbidden();

        $this->actingAs($admin)
            ->patch("/admin/reviews/{$review->id}", ['is_approved' => 0])
            ->assertRedirect('/admin/reviews');
        $this->assertDatabaseHas('reviews', ['id' => $review->id, 'is_approved' => false]);

        $this->patch("/admin/users/{$manager->id}", ['role' => 'user'])
            ->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', ['id' => $manager->id, 'role' => 'user']);
    }

    public function test_admin_cannot_remove_their_own_or_last_admin_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from('/admin/users')
            ->patch("/admin/users/{$admin->id}", ['role' => 'user'])
            ->assertRedirect('/admin/users')
            ->assertSessionHasErrors('role');

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
    }
}
