<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'code' => strtoupper(fake()->unique()->bothify('??-###')),
            'description' => fake()->optional()->paragraph(),
            'teacher_id' => User::factory(),
            'semester' => fake()->numberBetween(1, 8),
            'course' => fake()->numberBetween(1, 4),
            'credit' => fake()->numberBetween(2, 8),
            'is_open' => false,
            'is_active' => true,
        ];
    }

    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_open' => true,
        ]);
    }
}
