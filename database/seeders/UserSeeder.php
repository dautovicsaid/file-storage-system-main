<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                "name" => "Said",
                "email" => "said@gmail.com",
                "password" => Hash::make('password'),
                "storage_limit" => 1073741824,
                "is_admin" => true
//            'api_token' => Str::random(60)
            ],
            [
                "name" => "User",
                "email" => "user@gmail.com",
                "password" => Hash::make('password'),
                "storage_limit" => 1073741824,
                "is_admin" => false
//            'api_token' => Str::random(60)
            ],
        ];

        User::insert($users);
        User::factory(40)->create();
    }
}
