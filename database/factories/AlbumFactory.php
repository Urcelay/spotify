<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Album>
 */
class AlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_artist' => \App\Models\Artist::inRandomOrder()->first()->id ?? \App\Models\Artist::factory(),
            'name' => $this->faker->words(3, true),
            'cover_image' => $this->faker->imageUrl(300, 300, 'abstract', true),
            'release_year' => $this->faker->year,
            'total_songs' => $this->faker->numberBetween(5, 15),

        ];
    }
}
