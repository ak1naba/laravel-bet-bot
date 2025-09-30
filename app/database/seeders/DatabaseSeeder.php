<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'email' => 'admin@mail.ru',
            'password' => Hash::make('qweqweqwe'),
            'name' => 'admin',
            'role' => 'admin'
        ]);
    }
}
