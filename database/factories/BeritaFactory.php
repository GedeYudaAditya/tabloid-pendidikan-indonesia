<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Berita>
 */
class BeritaFactory extends Factory
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
            'kecamatan_id' => $this->faker->numberBetween(1, 100),
            'judul' => $this->faker->sentence(6),
            'isi' => $this->faker->paragraph(30),
            'user_id' => $this->faker->numberBetween(1, 10),
            'gambar' => '"no-data.jpeg"',
            'status' => $this->faker->randomElement(['publish', 'draft']),
            'slug' => $this->faker->slug(),
            'liputan_id' => $this->faker->numberBetween(1, 100),
        ];
    }
}
