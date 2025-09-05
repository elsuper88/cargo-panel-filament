<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateNativeUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'native:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user for NativePHP environment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->info('User created successfully!');
        $this->info('Email: admin@admin.com');
        $this->info('Password: password');
    }
}
