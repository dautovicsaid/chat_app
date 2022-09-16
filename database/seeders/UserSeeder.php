<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            "name" => "Said",
            "email" => "said@gmail.com",
            "password" => Hash::make('password'),
            'api_token' => Str::random(60)
        ];

        User::query()->create($user);
        User::factory(40)->create();

    }
}
