<<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('unauthenticated user cannot access product', function () {
    //through phunit
    $this->get('/products')->assertStatus(302)->assertRedirect('login');


    $response = $this->get('/products');

    expect($response->status())->toBe(302);
    expect($response->headers->get('Location'))->toBe(url('login'));
});

it('redirects to products after login', function () {
    $user = User::create([
        'name' => 'danish',
        'email' => 'dinesh@one.com',
        'password' => bcrypt('Danish@0808'),
    ]);

    $this->post('/login', [
        'email' => 'dinesh@one.com',
        'password' => 'Danish@0808',
    ])->assertStatus(302)->assertRedirect('products');
});
