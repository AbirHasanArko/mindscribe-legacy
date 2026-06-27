<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $genres = [
            ['name' => 'Technology', 'slug' => 'technology'],
            ['name' => 'Design', 'slug' => 'design'],
            ['name' => 'Culture', 'slug' => 'culture'],
            ['name' => 'Science', 'slug' => 'science'],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle'],
        ];

        foreach ($genres as $genre) {
            \App\Models\Genre::firstOrCreate(['slug' => $genre['slug']], $genre);
        }

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
