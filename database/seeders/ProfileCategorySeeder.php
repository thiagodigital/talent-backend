<?php

namespace Database\Seeders;

use App\Models\ProfileCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Dominância' => ['Ousado', 'Direto', 'Competitivo', 'Cooperador', 'Moderado', 'Diplomático', 'Modesto'],
            'Influência' => ['Carismático', 'Otimista', 'Persuasivo', 'Reservado', 'Formal', 'Desconfiado', 'Concentrado'],
            'Estabilidade' => ['Acolhedor', 'Paciente', 'Bom Ouvinte', 'Impulsivo', 'Acelerado', 'Multitarefa', 'Dinâmico'],
            'Conformidade' => ['Detalhista', 'Organizado', 'Analítico', 'Criativo', 'Informal', 'Flexível', 'Assume Riscos'],
        ];

        foreach ($data as $category => $traits) {
            $cat = ProfileCategory::create(['name' => $category]);

            foreach ($traits as $trait) {
                $cat->profileTraits()->create(['name' => $trait]);
            }
        }
    }
}
