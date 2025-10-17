<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class, // Assuming you have a PermissionSeeder
            RoleSeeder::class, // Assuming you have a RoleSeeder
            EvaluationSeeder::class,
            UserSeeder::class,
        ]);
    }
}
