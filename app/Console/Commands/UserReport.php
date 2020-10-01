<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class UserReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:user {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Specify a name or email address to get information about a user.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::where('email', '=', $this->argument('user'))
            ->orWhere('name', '=', $this->argument('user'))
            ->first();
        
        if (!empty($user)) {
            $this->info('Name: '.$user->name);
            $this->info('Email: '.$user->email);
            $this->info('Unique ID: '.$user->unique_id);
        } else {
            $this->error('User not found. You must specify a name or email address of an existing user account. If there are spaces in the name, please use quotes, e.g. "John Smith".');
        }

        return 0;
    }
}
