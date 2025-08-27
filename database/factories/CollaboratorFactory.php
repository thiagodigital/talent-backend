<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Collaborator>
 */
class CollaboratorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'  => fake()->name(),
            'email'  => fake()->email(),
            'phone'  => fake()->phoneNumber(),
            'parent_id'  => User::inRandomOrder()->first()->id,
            'role_id'  => 4,
        ];
    }
}
