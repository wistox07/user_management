<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->name(),
            "personal_email" => fake()->email(),
            "avatar" => fake()->word(),
            "birth" => fake()->date(),
            "gender" => fake()->randomElement(['m', 'f']),
            "status" => fake()->randomElement([0,1]),
            'created_by' => null,
            'updated_by' => null,
            'deleted_by' => null,
            'delete_at' => null
        ];
    }
}
