<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kecamatan>
 */
class KecamatanFactory extends Factory
{
    static $order = 0;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'kabupaten_id' => $this->faker->numberBetween(1, 9),
            'nama_kecamatan' => $data['name'] = $this->faker->unique()->city,
            'slug' => Str::slug($data['name']),
            'gambar' => 'no-data.jpeg',
        ];
    }
}
