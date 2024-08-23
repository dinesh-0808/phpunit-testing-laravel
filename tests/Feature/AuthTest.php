<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    public function test_unauthenticated_user_cannot_access_product(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    public function test_redirects_to_products_after_login(): void
    {
        $user = User::create([
            'name'=>'danish',
            'email'=>'dinesh@one.com',
            'password'=>bcrypt('Danish@0808')
        ]);
        $response = $this->post('/login',[
            'email'=>'dinesh@one.com',
            'password'=>'Danish@0808'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('products');

    }
}
