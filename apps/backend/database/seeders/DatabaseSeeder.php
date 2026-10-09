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
        User::create([
            'name' => 'Usuario Uno',
            'email' => 'usuario1@planazo.com',
            'password' => 'Planazo123!',
        ]);

        User::create([
            'name' => 'Usuario Dos',
            'email' => 'usuario2@planazo.com',
            'password' => 'Planazo123!',
        ]);
    }
}
