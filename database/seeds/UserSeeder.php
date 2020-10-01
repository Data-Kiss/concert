<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(App\User::class)->create();

        if (!empty($user)) {
            $this->command->info('User created. Unique ID: '.$user->unique_id);
        }         
    }
}
