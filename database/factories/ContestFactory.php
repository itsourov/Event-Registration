<?php

namespace Database\Factories;

use App\Models\Contest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ContestFactory extends Factory
{
    protected $model = Contest::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'slug' => $this->faker->slug(),
            'semester' => $this->faker->word(),
            'description' => $this->faker->text(),
            'registration_fee' => $this->faker->randomNumber(),
            'registration_deadline' => Carbon::now(),
            'countdown_text' => $this->faker->text(),
            'countdown_time' => Carbon::now(),
            'sections' => $this->faker->words(),
            'departments' => $this->faker->words(),
            'lab_teacher_names' => $this->faker->words(),
            'dates' => $this->faker->words(),
            'room_data' => $this->faker->words(),
            'extra' => $this->faker->words(),
            'registration_limit' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
