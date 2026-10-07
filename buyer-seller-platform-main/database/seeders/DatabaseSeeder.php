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
      

User::updateOrCreate(
    ['email' => 'test@example.com'],
    [
        'name' => 'Test User',
        'email_verified_at' => now(),
        // Only set password if you are using password login (you’re OTP, so it can be any)
        'password' => bcrypt('password'),
    ]
);


        $this->call([
            CitySeeder::class,


        ]);

        $this->call([
            CategorySeeder::class,
        ]);

    }
}
