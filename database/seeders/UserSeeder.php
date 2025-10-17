<?php

namespace Database\Seeders;

use App\Jobs\ProcessExamDiskJob;
use App\Models\Collaborator;
use App\Models\ProfileTrait;
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
        $user = User::factory()->create([
            'name' => 'Thiago Augusto',
            'email' => 'thiago@site.com',
            'password' => bcrypt('password'), // Use a secure password hashing method
        ])->assignRole('admin'); // Assuming you have a role system in place

        Collaborator::factory()->count(2)->create([
            'parent_id' => $user->id,
        ]);

        $user2 = User::factory()->create([
            'name' => 'Jose Ricardo',
            'email' => 'jrer@uol.com.br',
            'password' => bcrypt('senhaForte123'), // Use a secure password hashing method
        ])->assignRole('admin'); // Assuming you have a role system in place

        Collaborator::factory()->count(2)->create([
            'parent_id' => $user2->id,
        ]);
    }
}
