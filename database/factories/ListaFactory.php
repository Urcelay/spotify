<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lista>
 */
class ListaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => \App\Models\User::inRandomOrder()->first()->id ?? \App\Models\User::factory(),
            'name' => $this->faker->words(3, true),
            'is_public' => $this->faker->boolean(30),
        ];
    }
}
