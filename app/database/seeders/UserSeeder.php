<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ru_RU');

        // 5 админов
        for ($i = 0; $i < 5; $i++) {
            User::create([
                'name' => $faker->lastName . ' ' . $faker->firstName,
                'email' => 'admin' . $i . '@example.com',
                'password' => Hash::make('password'), // общий пароль для теста
                'role' => 'admin',
            ]);
        }

        // 100 обычных пользователей
        for ($i = 0; $i < 100; $i++) {
            User::create([
                'name' => $faker->lastName . ' ' . $faker->firstName,
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'role' => 'user',
            ]);
        }
    }
}
