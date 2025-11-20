<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MusicDetail>
 */
class MusicDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_lista' => \App\Models\Lista::inRandomOrder()->first()->id ?? \App\Models\Lista::factory(),
            'id_music' => \App\Models\Music::inRandomOrder()->first()->id ?? \App\Models\Music::factory(),
            'order' => $this->faker->numberBetween(1, 50),
        ];
    }
}
