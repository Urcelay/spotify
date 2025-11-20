<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Music>
 */
class MusicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'id_category' => \App\Models\Category::inRandomOrder()->first()->id ?? \App\Models\Category::factory(),
            'id_artist' => \App\Models\Artist::inRandomOrder()->first()->id ?? \App\Models\Artist::factory(),
            'id_album' => \App\Models\Album::inRandomOrder()->first()->id ?? \App\Models\Album::factory(),
            'duration' => $this->faker->time('i:s'),
            'size' => $this->faker->numberBetween(2000, 5000), // en kB
            'file_name' => $this->faker->slug . '.mp3',
            'file_url' => $this->faker->url,
            'cover_image' => $this->faker->imageUrl(300, 300, 'music', true),
            'play_count' => $this->faker->numberBetween(0, 1000),
            'likes' => $this->faker->numberBetween(0, 500),
            'is_featured' => $this->faker->boolean(20),

        ];
    }
}
