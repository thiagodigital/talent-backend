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
            'email' => 'jrer@uol.com.br',
            'password' => bcrypt('senhaForte123'), // Use a secure password hashing method
        ])->assignRole('admin'); // Assuming you have a role system in place

        $positions = ['Desenvolvedor', 'Designer', 'Auxiliar de producao', 'Operador de Caixa', 'Gerente de Loja'];

        $collaborators = Collaborator::factory()->count(5)->create([
            'parent_id' => $user->id,
            'position' => $positions[array_rand($positions)],
        ]);

        foreach ($collaborators as $collaborator) {
            $countarray = [0,25,50,75,100];
            $traitCollection = ProfileTrait::select('id')->get()
                ->mapWithKeys(function ($trait) use ($collaborator, $countarray) {
                    return [
                        $trait->id => [
                            'collaborator_id'   => $collaborator->id,
                            'profile_trait_id'  => $trait->id,
                            'score'             => $countarray[array_rand([0,25,50,75,100], 1)] ?? 20,
                        ]
                    ];
                })->toArray();
                $collaborator->profileTraits()->sync($traitCollection);
            dispatch(new ProcessExamDiskJob($collaborator->id));
        }

        $user2 = User::factory()->create([
            'name' => 'Jose Ricardo',
            'email' => 'jrer@uol.com.br',
            'password' => bcrypt('senhaForte123'), // Use a secure password hashing method
        ])->assignRole('admin'); // Assuming you have a role system in place
        $collaborators2 = Collaborator::factory()->count(2)->create([
            'parent_id' => $user2->id,
            'position' => $positions[array_rand($positions)],
        ]);
        foreach ($collaborators2 as $collaborator2) {
            $countarray = [0,25,50,75,100];
            $traitCollection2 = ProfileTrait::select('id')->get()
                ->mapWithKeys(function ($trait) use ($collaborator2, $countarray) {
                    return [
                        $trait->id => [
                            'collaborator_id'   => $collaborator2->id,
                            'profile_trait_id'  => $trait->id,
                            'score'             => $countarray[array_rand([0,25,50,75,100], 1)] ?? 25,
                        ]
                    ];
                })->toArray();
            $collaborator2->profileTraits()->sync($traitCollection2);
            dispatch(new ProcessExamDiskJob($collaborator2->id));
        }
    }
}
