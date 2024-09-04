<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Opening>
 */
class OpeningFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->text,
            'location' => $this->faker->city,
            'employment_type' => $this->faker->randomElement(['full-time', 'part-time', 'internship']),
            'work_mode' => $this->faker->randomElement([
                'office',
                'hybrid',
                'remote'
            ]),
            'posted_at' => now(),
            'expires_at' => $this->faker->dateTimeBetween(
                'now',
                '+1 month'
            ),

        ];
    }
}
