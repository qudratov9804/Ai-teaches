<?php

namespace Database\Factories;

use App\Models\Material;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject_id' => Subject::factory(),
            'topic_id' => null,
            'title' => fake()->sentence(4),
            'author' => fake()->optional()->name(),
            'description' => fake()->optional()->paragraph(),
            'type' => fake()->randomElement(array_keys(Material::types())),
            'file_path' => 'materials/sample-'.fake()->uuid().'.pdf',
            'original_name' => fake()->slug().'.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => fake()->numberBetween(20_000, 10_000_000),
            'published_year' => fake()->numberBetween(1990, 2026),
            'is_active' => true,
            'uploaded_by' => User::factory(),
        ];
    }
}
