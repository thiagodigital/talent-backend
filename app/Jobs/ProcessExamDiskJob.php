<?php

namespace App\Jobs;

use App\Models\Collaborator;
use App\Models\CollaboratorEvaluation;
use App\Services\DiscEvaluatorService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OpenAI\Laravel\Facades\OpenAI;

class ProcessExamDiskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $collaboratorId) {}

    public function handle()
    {
        $collaborator = Collaborator::with('profileTraits')->find($this->collaboratorId);

        // Monta o payload a ser enviado ao GPT
        $payload = [
            'id' => $collaborator->id,
            'name' => $collaborator->name,
            'position' => $collaborator->position,
            'traits' => $collaborator->profileTraits->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'score' => $t->pivot->score,
                'category' => $t->profileCategory->name,
            ])
        ];

        // Chama API do GPT
        $evaluation = DiscEvaluatorService::evaluate($collaborator, $payload);

        // 🔹 Salva no histórico de avaliações
        CollaboratorEvaluation::create([
            'collaborator_id' => $collaborator->id,
            'position'        => $collaborator->position,
            'feedback'        => $evaluation['feedback'],
            'opinion'         => $evaluation['opinion'],
            'points'          => $evaluation['points'],
            'positions'       => $evaluation['positions'],
        ]);
    }
}
