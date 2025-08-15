<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'gestor']);
        $hr = Role::create(['name' => 'rh']);
        $collaborator = Role::create(['name' => 'colaborador']);

        // Admin tem tudo
        $admin->givePermissionTo(Permission::all());

        // Gestor pode gerenciar colaboradores e planos
        $manager->givePermissionTo([
            'view collaborators',
            'create collaborators',
            'edit collaborators',

            'assign skills',
            'assign desired skills',

            'generate development plan',
            'view development plan',

            'create feedback',
            'view feedback',
        ]);

        // RH pode visualizar tudo, gerar relatório de risco
        $hr->givePermissionTo([
            'view collaborators',
            'view development plan',
            'view feedback',

            'view risk report',
            'generate risk report',
        ]);

        $collaborator->givePermissionTo([
            'view tests',
            'view trails',
        ]);
    }
}
