<?php

namespace Database\Factories;

use App\Models\Curriculum;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Curriculum>
 */
class CurriculumFactory extends Factory
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
            'title' => "Ishchi o'quv dasturi",
            'file_path' => 'curricula/sample-'.fake()->uuid().'.pdf',
            'original_name' => 'ishchi-oquv-dasturi.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => fake()->numberBetween(50_000, 5_000_000),
            'description' => fake()->optional()->sentence(),
            'version' => 1,
            'is_active' => true,
            'uploaded_by' => User::factory(),
        ];
    }
}
