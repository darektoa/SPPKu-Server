<?php

namespace Database\Seeders;

use App\Helpers\UsernameHelper;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $payer = User::create([
            'name'      => 'User',
            'username'  => UsernameHelper::make('user'),
            'email'     => 'user@gmail.com',
            'password'  => Hash::make('password'),
        ]);

        $school = User::create([
            'name'      => 'School',
            'username'  => UsernameHelper::make('school'),
            'email'     => 'school@gmail.com',
            'password'  => Hash::make('password'),
        ]);

        $payer->payer()->create();
        $school->school()->create();

        $payer->tokens()->create(['token' => '$2y$10$OyJvAmHHfe/3fcOSFBIetOOn9I6rw5V0bFQVF5HNCFGVcBCAKtzNq']);
        $school->tokens()->create(['token' => '$2y$10$ZTwgm6OFd/Zj8zZ3BhpO7urCEjJ1ECwFUFTq5MPOyXQag6jKmWhY6']);
    }
}
