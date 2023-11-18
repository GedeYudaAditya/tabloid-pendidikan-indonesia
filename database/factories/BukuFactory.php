<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Buku>
 */
class BukuFactory extends Factory
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
            'penulis' => $this->faker->name(),
            'sinopsis' => $this->faker->paragraph(3),
            'gambar' => 'no-data.jpeg',
            'slug' => $this->faker->slug(),
        ];
    }
}
