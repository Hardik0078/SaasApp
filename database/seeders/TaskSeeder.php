<?php

namespace Database\Seeders;
use Faker\Factory as Faker;

use App\Models\Task;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TaskSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 5; $i++)
            {
            Task::query()->updateOrCreate(
                [
                    "name" => $faker->sentence(3),
                    'description' => $faker->paragraph,
                    'status' => $faker->randomElement(['pending', 'in_progress', 'completed']),
                    'due_date' => now()->addDays(rand(1, 30)),
                ],
            );
        }


    }
}
