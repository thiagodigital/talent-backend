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
            "Dominante" => ["Autoconfiante", "Independente", "Dominante", "Pró-ativo", "Empreendedor", "Corajoso", "Prático", "Rápido", "Eficiente", "Objetivo", "Assertivo", "Focado em Resultados", "Determinado", "Firme", "Energético", "Lutador", "Combativo", "Agressivo", "Automotivo", "Pioneiro", "Impulsionador", "Resolvedor", "Destemido", "Desafiador", "Competitivo", "Assumeriscos", "Desbravador"],
            "Influente" => ["Comunicativo", "Alegre", "Extrovertido", "Participativo", "Relacional", "Flexível", "Persuasivo", "Contagiante", "Estimulador", "Preza pelo prazer", "Emotivo", "Divertido", "Criativo", "Falante", "Distraído", "Participativo", "Facilitador", "Influenciador", "Articulador", "Empolgante", "Motivador", "Vaidoso", "Simpático", "Gosta de ser reconhecido", "Entusiasmado", "Impulsivo", "Otimista"],
            "Estável"   => ["Acolhedor", "Amigável", "Paciente", "Agradável", "Tranquilo", "Organizado", "Calmo", "Rotineiro", "Constante", "Conciliador", "Conselheiro", "Bom ouvinte", "Cometido", "Amável", "Mediador", "Auto-controlado", "Conservador", "Responsável", "Persistente", "Prevenido", "Tolerante", "Aconselhador", "Harmônico", "Apoiador", "Moderado", "Equilibrado", "Estável"],
            "Analista"  => ["Autodisciplinado", "Atento a detalhes", "Diligente", "Criterioso", "Cuidadoso", "Especialista", "Idealizador", "Perfeccionista", "Uniforme", "Conforme", "Sistemático", "Sensato", "Preciso", "Lógico", "Racional", "Profundo", "Perceptivo", "Estratégico", "Exato", "Exigente", "Estruturado", "Ponderado", "Ordenador", "Analisador", "Teórico", "Conservador", "Aprofunda conhecimentos"],
        ];

        foreach ($data as $category => $traits) {
            $cat = ProfileCategory::create([
                'name' => $category,
                'color' => match ($category) {
                    'Dominante' => 'red',
                    'Influente' => 'yellow',
                    'Estável' => 'green',
                    'Analista' => 'blue',
                    default => 'gray',
                },
            ]);

            foreach ($traits as $trait) {
                $cat->profileTraits()->create(['name' => $trait]);
            }
        }
    }
}
