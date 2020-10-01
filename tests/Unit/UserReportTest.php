<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserReportTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = \Faker\Factory::create();
    }

    /** @test */
    public function it_returns_an_error_message_if_user_not_found()
    {  
        $input = $this->faker->name;

        $this->artisan('report:user', ['user' => $input])
            ->expectsOutput('User not found. You must specify a name or email address of an existing user account. If there are spaces in the name, please use quotes, e.g. "John Smith".')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_returns_the_user_details_if_an_existing_email_is_entered()
    {
        $user = factory(\App\User::class)->create();

        $this->artisan('report:user', ['user' => $user->email])
            ->expectsOutput('Name: '.$user->name)
            ->expectsOutput('Email: '.$user->email)
            ->expectsOutput('Unique ID: '.$user->unique_id)
            ->assertExitCode(0);
    }

    /** @test */
    public function it_returns_the_user_details_if_an_existing_name_is_entered()
    {
        $user = factory(\App\User::class)->create();

        $this->artisan('report:user', ['user' => $user->name])
            ->expectsOutput('Name: '.$user->name)
            ->expectsOutput('Email: '.$user->email)
            ->expectsOutput('Unique ID: '.$user->unique_id)
            ->assertExitCode(0);
    }


}
