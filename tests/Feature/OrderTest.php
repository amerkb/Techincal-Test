<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Statuses\UserStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    protected function createUser($isAdmin = false)
    {
        return User::factory()->create([
            'password' => '12345678',
            'user_type' => $isAdmin ? UserStatus::ADMIN : UserStatus::USER,
        ]);
    }

    protected function getTokenForUser($user)
    {
        return auth()->login($user);
    }

    public function it_can_create_an_order_successfully()
    {
        $product1 = Product::factory()->create(['price' => 100, 'quantity' => 50]);
        $product2 = Product::factory()->create(['price' => 200, 'quantity' => 30]);

        $data = [
            'products' => [
                ['product_id' => $product1->id, 'qty' => 5],
                ['product_id' => $product2->id, 'qty' => 2],
            ],
        ];

        $response = $this->postJson('/api/User/makeOrder', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'total' => 500,
        ]);

        $order = Order::where('user_id', $this->user->id)->first();
        $this->assertCount(2, $order->products);
    }

    public function it_fails_to_create_order_with_insufficient_stock()
    {
        $user = $this->createUser();

        $token = $this->getTokenForUser($user);

        $product = Product::factory()->create(['price' => 100, 'quantity' => 2]);

        $data = [
            'products' => [
                ['product_id' => $product->id, 'qty' => 5],
            ],
        ];

        $response = $this->postJson('/api/User/makeOrder', $data, [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(400);
        $this->assertDatabaseMissing('orders', [
            'user_id' => $user->id,
        ]);

        $this->assertEquals(
            'Not enough stock for product with ID '.$product->id.'. Available quantity: 2.',
            $response->json('message')
        );
    }

    /** @test */
    public function it_fails_to_create_order_with_invalid_product_id()
    {
        $user = $this->createUser();

        $token = $this->getTokenForUser($user);

        $data = [
            'products' => [
                ['product_id' => 999, 'qty' => 1],
            ],
        ];

        $response = $this->postJson('/api/User/makeOrder', $data, [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('orders', [
            'user_id' => $user->id,
        ]);

    }
}
