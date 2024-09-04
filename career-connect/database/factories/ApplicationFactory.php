<?php

namespace Database\Factories;

use App\Models\Opening;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'opening_id' => Opening::factory(),
            'student_id' => Student::factory(),
            'status' => $this->faker->randomElement([
                'applied',
                'interviewing',
                'accepted',
                'rejected'
            ]),
            'applied_at' => now(),
        ];
    }
}
