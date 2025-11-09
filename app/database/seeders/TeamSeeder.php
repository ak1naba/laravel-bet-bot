<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sport;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $sports = Sport::all();
        foreach ($sports as $sport) {
            for ($i = 1; $i <= 10; $i++) {
                Team::create([
                    'name' => $sport->name . ' Team ' . $i,
                    'sport_id' => $sport->id,
                ]);
            }
        }
    }
}
