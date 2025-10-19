<?php

namespace Database\Seeders;

use App\Models\Sport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sports = [
            'Футбол',
            'Баскетбол',
            'Волейбол',
            'Хоккей',
            'Теннис',
            'Настольный теннис',
            'Бокс',
            'Плавание',
            'Лёгкая атлетика',
            'Гандбол',
            'Бейсбол',
            'Формула-1',
            'Биатлон',
            'Фигурное катание',
            'Киберспорт',
        ];

        foreach ($sports as $name) {
            Sport::firstOrCreate(['name' => $name]);
        }
    }
}
