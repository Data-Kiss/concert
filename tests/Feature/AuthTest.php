<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    private $user;
    private $faker;

    public function setUp(): void
    {
        parent::setUp();
        //$this->withoutExceptionHandling();
        $this->faker = \Faker\Factory::create();
    }

    public function validUser(array $override = []) {
        $password = $this->faker->password(18);

        return array_merge([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'unique_id' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'password' => $password,
            'password_confirmation' => $password,
        ], $override);
    }

    /** @test */
    public function a_new_registration_requires_the_unique_id()
    {
        $invalidUser = $this->validUser([
            'unique_id' => null
        ]);

        $response = $this->post('/register', $invalidUser)
            ->assertSessionHasErrors()
            ->assertStatus(302);
    }

    /** @test */
    public function a_user_can_register()
    {
        $user = $this->validUser();

        $response = $this->post('/register', $user)
            ->assertStatus(302);

        $this->assertDatabaseHas('users', ['email' => $user['email']]);
    }

    /** @test */
    public function an_unverified_user_cannot_access_the_home_page()
    {
        $user = factory(\App\User::class)->create([
            'email_verified_at' => null
        ]);

        $response = $this->actingAs($user)
            ->get('/home')
            ->assertStatus(302);
    }

    /** @test */
    public function a_verified_user_can_access_the_home_page()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)
            ->get('/home')
            ->assertStatus(200);
    }
}
