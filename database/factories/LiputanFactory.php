<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Liputan>
 */
class LiputanFactory extends Factory
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
            'isi' => $this->faker->paragraph(3),
            'gambar' => '"no-data.jpeg"',
            'status' => $this->faker->randomElement(['mengantri', 'dibuat']),
            'slug' => $this->faker->slug(),
            'reporter_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
