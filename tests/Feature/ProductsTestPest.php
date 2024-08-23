<?php

use App\Models\Product;
use App\Models\User;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\patch;
use function Pest\Laravel\delete;
use function Pest\Laravel\getJson;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = createUser();
});

test('homepage contains non-empty table', function () {
    // $product = Product::create([
    //     'name' => 'prod 1',
    //     'price' => 10
    // ]);

    // $response = $this->actingAs($this->user)->get('/products');

    // $response->assertStatus(200)
    //          ->assertSee('prod')
    //          ->assertViewHas('products', fn ($collection) => !$collection->contains($product));
    $response = $this->actingAs($this->user)->get('/products');

    $product = Product::create([
        'name' => 'prod 1',
        'price' => 10
    ]);

    expect($response->status())->toBe(200);

    expect($response->content())->toContain('prod');

    $response->assertViewHas('products', function ($collection) use ($product) {
        return !$collection->contains($product);
    });
});

test('paginated products table doesnt contain 11th record', function () {
    $products = Product::factory(11)->create();
    $lastProduct = $products->last();

    $response = $this->actingAs($this->user)->get('/products');

    $response->assertStatus(200)
             ->assertViewHas('products', fn ($collection) => !$collection->contains($lastProduct));
});

test('admin can see products create button', function () {
    $admin = createUser(isAdmin: true);

    $response = $this->actingAs($admin)->get('/products');

    $response->assertStatus(200)
             ->assertSee('create product');
});

test('non-admin cannot see products create button', function () {
    $response = $this->actingAs($this->user)->get('/products');

    $response->assertStatus(200)
             ->assertDontSee('create product');
});

test('admin can access products create page', function () {
    $admin = createUser(isAdmin: true);

    $response = $this->actingAs($admin)->get('/products/create');

    $response->assertStatus(200);
});

test('non-admin cannot access products create page', function () {
    $response = $this->actingAs($this->user)->get('/products/create');

    $response->assertStatus(403);
});

test('admin can create product successfully', function () {
    $product = [
        'name' => "phone",
        'price' => 100
    ];
    $admin = createUser(isAdmin: true);

    $response = $this->actingAs($admin)->post('/products', $product);

    $response->assertStatus(302)
             ->assertRedirect('products');

    $this->assertDatabaseHas('products', $product);

    $lastProduct = Product::latest()->first();
    expect($lastProduct->name)->toBe($product['name']);
    expect($lastProduct->price)->toBe($product['price']);
});

test('edit page contains correct values', function () {
    $product = Product::factory()->create();
    $admin = createUser(isAdmin: true);

    $response = $this->actingAs($admin)->get("products/{$product->id}");

    $response->assertStatus(200)
             ->assertSee('value="'.$product->name.'"', false)
             ->assertSee('value="'.$product->price.'"', false);
});

test('product update validation error redirects back to form', function () {
    $product = Product::factory()->create();
    $admin = createUser(isAdmin: true);

    $response = $this->actingAs($admin)->patch("products/{$product->id}", [
        'name' => '',
        'price' => ''
    ]);

    $response->assertStatus(302)
             ->assertInvalid(['name', 'price']);
});

test('product delete is successful', function () {
    $product = Product::factory()->create();
    $admin = createUser(isAdmin: true);

    $response = $this->actingAs($admin)->delete("products/{$product->id}");

    $response->assertStatus(302)
             ->assertRedirect('/products');

    $this->assertDatabaseMissing('products', $product->toArray());
    $this->assertDatabaseCount('products', 0);
});

test('api returns products list', function () {
    $product = Product::factory()->create();
    $admin = createUser(isAdmin: true);

    $response = $this->actingAs($admin)->getJson('/product/api');

    $response->assertJsonFragment($product->toArray());


});

function createUser(bool $isAdmin = false): User
{
    return User::factory()->create(['is_admin' => $isAdmin]);
}


it('adds numbers correctly', function ($a, $b, $expected) {
    expect($a + $b)->toBe($expected);
})->with([
    [1, 1, 2],
    [2, 2, 4],
    [3, 3, 6],
]);

it('can add numbers', function () {
    $sum = 1 + 1;
    expect($sum)->toBe(2);
});
