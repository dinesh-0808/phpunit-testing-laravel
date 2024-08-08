<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
    }


    // public function test_homepage_contains_empty_table(): void
    // {

    //     $response = $this->actingAs($this->user)->get('/products');

    //     $response->assertStatus(200);
    //     $response->assertSee(__('product not found...'));
    // }

    public function test_homepage_contains_non_empty_table(): void
    {

        $response = $this->actingAs($this->user)->get('/products');
        $product = Product::create([
            'name' => 'prod 1',
            'price' => 10
        ]);
        $response->assertStatus(200);
        // $response->assertDontSee(__('product not found...'));
        $response->assertSee('prod');
        $response->assertViewHas('products',function ($collection) use ($product) {
            return !$collection->contains($product);
        });
    }

    public function test_paginated_products_table_doesnt_contian_11th_record()
    {
        $products = Product::factory(11)->create();
        $lastProduct = $products->last();

        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);
        $response->assertViewHas('products',function ($collection) use ($lastProduct){
            return !$collection->contains($lastProduct);
        });
    }

    public function test_admin_can_see_products_create_button()
    {
        $admin = $this->createUser(isAdmin: true);
        $response = $this->actingAs($admin)->get('/products');
        $response->assertStatus(200);
        $response->assertSee('create product');
    }

    public function test_admin_cannot_see_products_create_button()
    {
        $response = $this->actingAs($this->user)->get('/products');
        $response->assertStatus(200);
        $response->assertDontSee('create product');
    }

    public function test_admin_can_access_products_create_button()
    {
        $admin = $this->createUser(isAdmin: true);
        $response = $this->actingAs($admin)->get('/products/create');
        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_products_create_button()
    {
        $response = $this->actingAs($this->user)->get('/products/create');
        $response->assertStatus(403);
    }

    public function test_admin_created_product_successfully()
    {

        $product = [
            'name' => "phone",
            'price' => 100
        ];
        $admin = $this->createUser(isAdmin: true);
        $response= $this->actingAs($admin)->post('/products',$product);
        $response->assertStatus(302);
        $response->assertRedirect('products');
        $this->assertDatabaseHas('products',$product);

        $lastProduct = Product::latest()->first();
        $this->assertEquals($product['name'],$lastProduct->name);
        $this->assertEquals($product['price'],$lastProduct->price);

    }

    public function test_edit_contains_correct_values()
    {
        $product = Product::factory()->create();
        $admin = $this->createUser(isAdmin: true);
        $response = $this->actingAs($admin)->get('products/'.$product->id);
        $response->assertStatus(200);
        $response->assertSee('value="'.$product->name.'"' , escape:false);
        $response->assertSee('value="'.$product->price.'"', escape:false);

    }

    public function test_product_update_validation_error_redirects_back_to_form()
    {
        $product = Product::factory()->create();
        $admin = $this->createUser(isAdmin: true);
        $response = $this->actingAs($admin)->patch('products/'.$product->id,[
            'name' => '',
            'price' => ''
        ]);

        $response->assertStatus(302);
        // $response->assertSessionHasErrors(['name']);
        $response->assertInvalid(['name','price']); //for both
    }

    public function test_product_delete_successful()
    {
        $product = Product::factory()->create();
        $admin = $this->createUser(isAdmin: true);
        $response = $this->actingAs($admin)->delete('products/'.$product->id);
        $response->assertStatus(302);
        $response->assertRedirect('/products');
        $this->assertDatabaseMissing('products',[$product]);
        $this->assertDatabaseCount('products',0);
    }

    //json tests -----------------------------------------------------------------
    public function test_api_returns_products_list()
    {
        $product = Product::factory()->create();
        $admin = $this->createUser(isAdmin: true);
        $response = $this->actingAs($admin)->getJson('/products/api');
        $response->assertJsonFragment($product->toArray());
    }

    private function createUser(bool $isAdmin = false): User
    {
        return User::factory()->create(['is_admin'=>$isAdmin]);

    }
}
