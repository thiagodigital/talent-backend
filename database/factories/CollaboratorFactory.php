<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $provedor = ['@gmail.com', '@uol.com.br', '@oultook.com', '@hotmail.com', '@met.com', '@yahoo.com.br', '@bol.com.br', '@icloud.com'];
        $name = fake()->name();
        $positions = ['Desenvolvedor', 'Designer', 'Auxiliar de producao', 'Operador de Caixa', 'Gerente de Loja'];

        return [
            'name'  => $name,
            'email'  => Str::slug($name, '.') . fake()->randomElement($provedor, 1),
            'phone'  => fake()->phoneNumber(),
            'parent_id'  => User::inRandomOrder()->first()->id,
            'position' => fake()->randomElement($positions, 1),
            'role_id'  => 4,
        ];
    }
}
