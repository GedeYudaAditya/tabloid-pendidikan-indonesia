<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'judul' => $this->faker->sentence(6),
            'isi' => $this->faker->paragraph(3),
            'gambar' => 'no-data.jpeg',
            'jenis' => $this->faker->randomElement(['seminar', 'workshop']),
            'slug' => $this->faker->slug(),
            'user_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
