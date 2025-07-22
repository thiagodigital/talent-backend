<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Thiago Augusto',
            'email' => 'thiago@site.com',
            'password' => bcrypt('password'), // Use a secure password hashing method
        ])->assignRole('admin'); // Assuming you have a role system in place
    }
}
