<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Statuses\UserStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

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

    public function only_admin_can_store_a_product()
    {
        $user = $this->createUser(false);
        $token = $this->getTokenForUser($user);

        $data = [
            'name' => 'Sample Product',
            'description' => 'Product description',
            'quantity' => 10,
            'price' => 29.99,
        ];

        $response = $this->postJson('/api/Admin/product', $data, [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(403);
    }

    public function admin_can_store_a_product()
    {
        $admin = $this->createUser(true);
        $token = $this->getTokenForUser($admin);

        $data = [
            'name' => 'Sample Product',
            'description' => 'Product description',
            'quantity' => 10,
            'price' => 29.99,
        ];

        $response = $this->postJson('/api/Admin/product', $data, [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('products', [
            'name' => 'Sample Product',
        ]);
    }

    public function only_admin_can_update_a_product()
    {
        $admin = $this->createUser(true);
        $token = $this->getTokenForUser($admin);

        $product = Product::create([
            'name' => 'Old Product',
            'description' => 'Old description',
            'quantity' => 5,
            'price' => 19.99,
        ]);

        $data = [
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'quantity' => 15,
            'price' => 39.99,
        ];

        $response = $this->putJson("/api/Admin/product/{$product->id}", $data, [
            'Authorization' => 'Bearer '.$token,
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'name' => 'Updated Product',
        ]);
    }

    public function only_admin_can_delete_a_product()
    {
        $admin = $this->createUser(true);
        $token = $this->getTokenForUser($admin);

        $product = Product::create([
            'name' => 'Product to Delete',
            'description' => 'Description',
            'quantity' => 2,
            'price' => 9.99,
        ]);

        $response = $this->deleteJson("/api/Admin/product/{$product->id}", [], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(200);

    }

    public function regular_user_cannot_delete_product()
    {
        $user = $this->createUser(false);
        $token = $this->getTokenForUser($user);

        $product = Product::create([
            'name' => 'Product to Delete',
            'description' => 'Description',
            'quantity' => 2,
            'price' => 9.99,
        ]);

        $response = $this->deleteJson("/api/Admin/product/{$product->id}", [], [
            'Authorization' => 'Bearer '.$token,
        ]);
        $response->assertStatus(403);
    }

    public function admin_can_get_products()
    {

        $admin = $this->createUser(true);
        $token = $this->getTokenForUser($admin);
        // Create products
        Product::factory()->create(['name' => 'Product 1']);
        Product::factory()->create(['name' => 'Product 2']);

        $response = $this->getJson('/api/product', [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    public function user_can_get_products()
    {

        $admin = $this->createUser(false);
        $token = $this->getTokenForUser($admin);
        // Create products
        Product::factory()->create(['name' => 'Product 1']);
        Product::factory()->create(['name' => 'Product 2']);

        $response = $this->getJson('/api/product', [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }
}
