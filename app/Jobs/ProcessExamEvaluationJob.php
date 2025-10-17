<?php

namespace App\Jobs;

use App\Models\Collaborator;
use App\Models\CollaboratorEvaluation;
use App\Services\EvaluationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessExamEvaluationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $collaboratorId, public array $skills) {}

    public function handle(): void
    {
        $collaborator = Collaborator::with('profileEvaluations')->find($this->collaboratorId);

        // Monta o payload a ser enviado ao GPT
        $payload = [
            'id' => $collaborator->id,
            'name' => $collaborator->name,
            'position' => $collaborator->position,
            'hardskills' => $this->skills['hardskills'],
            'softskills' => $this->skills['softskills']
        ];

        // Chama API do GPT
        $evaluation = EvaluationService::evaluate($collaborator, $payload);

        // 🔹 Salva no histórico de avaliações
        CollaboratorEvaluation::create([
            'collaborator_id' => $collaborator->id,
            'feedback'  => $evaluation['feedback'] ?? '',
            'opinion'   => $evaluation['opinion'],
            'points'    => $evaluation['points'],  // array
            'position' => $collaborator->position ?? 'vendedor', // array
        ]);
    }
}
