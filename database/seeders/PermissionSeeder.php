<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Colaboradores
            'view collaborators',
            'create collaborators',
            'edit collaborators',
            'delete collaborators',

            // Habilidades
            'assign skills',
            'assign desired skills',

            // Planos de desenvolvimento
            'generate development plan',
            'view development plan',

            // Feedbacks
            'create feedback',
            'view feedback',

            // Risco
            'view risk report',
            'generate risk report',

            // Admin
            'manage roles',
            'manage permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
