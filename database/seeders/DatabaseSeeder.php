<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Artikle;
use App\Models\Berita;
use App\Models\Buku;
use App\Models\Event;
use App\Models\Jurnal;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Liputan;
use App\Models\Program;
use App\Models\SistemInformasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Kabupaten::factory(9)->create();
        Kecamatan::factory(100)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'level' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Redaksi User',
            'email' => 'redaksi@example.com',
            'password' => bcrypt('password'),
            'level' => 'redaksi',
        ]);

        User::factory()->create([
            'name' => 'Reporter User',
            'email' => 'reporter@example.com',
            'password' => bcrypt('password'),
            'level' => 'reporter',
        ]);

        User::factory()->create([
            'name' => 'Jurnalis User',
            'email' => 'jurnalis@example.com',
            'password' => bcrypt('password'),
            'level' => 'jurnalis',
        ]);

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'level' => 'user',
        ]);

        User::factory(100)->create();

        // Liputan::factory(100)->create();
        // Berita::factory(100)->create();

        // Buku::factory(100)->create();
        // Event::factory(100)->create();

        // Jurnal::factory(100)->create();
        // Artikle::factory(100)->create();

        SistemInformasi::factory(10)->create();
        Program::factory(10)->create();
    }
}
