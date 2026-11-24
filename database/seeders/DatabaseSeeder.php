<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@laundry.com'],
            [
                'name' => 'Administrator Laundry',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'kasir@laundry.com'],
            [
                'name' => 'Kasir Laundry',
                'password' => Hash::make('password'),
                'role' => User::ROLE_KASIR,
                'email_verified_at' => now(),
            ],
        );

        $this->call([
            LayananSeeder::class,
            TransaksiSeeder::class,
        ]);
    }
}
