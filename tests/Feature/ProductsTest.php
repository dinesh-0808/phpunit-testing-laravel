<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;
    // public function test_homepage_contains_empty_table(): void
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($user)->get('/products');

    //     $response->assertStatus(200);
    //     $response->assertSee(__('product not found...'));
    // }

    public function test_homepage_contains_non_empty_table(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/products');
        $product = Product::create([
            'name' => 'prod 1',
            'price' => 10
        ]);
        $response->assertStatus(200);
        $response->assertDontSee(__('product not found...'));
        $response->assertSee('prod');
        $response->assertViewHas('products',function ($collection) use ($product) {
            return $collection->contains($product);
        });
    }

    public function test_paginated_products_table_doesnt_contian_11th_record()
    {
        $products = Product::factory(11)->create();
        $lastProduct = $products->last();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(200);
        $response->assertViewHas('products',function ($collection) use ($lastProduct){
            return !$collection->contains($lastProduct);
        });
    }
}
