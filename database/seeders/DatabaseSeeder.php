<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Mario',
            'email' => 'mariovalcen@gmail.com',
            'password' => bcrypt('12345678'),
        ]);


        \App\Models\User::factory(10)->create();
    }
}
