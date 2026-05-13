<?php

namespace Database\Seeders;
use Faker\Factory as Faker;

use App\Models\Project;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProjectSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 5; $i++)
            {
            Project::query()->updateOrCreate(
                [
                    "name" => $faker->name(),
                    'description' => $faker->paragraph,
                    'start_date' => now()->subDays(rand(10, 30)),
                    'end_date' => now()->addDays(rand(30, 90)),
                ],
            );
        }


    }
}
