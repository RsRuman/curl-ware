<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name     = $this->ask('Enter admin name');
        $email    = $this->ask('Enter admin email');
        $password = $this->secret('Enter admin password');

        $user = User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => bcrypt($password)
        ]);

        // Assign role
        $user->assignRole('admin');


        $this->info('Admin user created!');
    }
}
