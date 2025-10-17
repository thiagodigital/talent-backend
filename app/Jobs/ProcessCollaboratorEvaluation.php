<?php

namespace App\Jobs;

use App\Models\Collaborator;
use App\Models\CollaboratorEvaluation;
use App\Services\OpenAIEvaluationService;
use App\Events\EvaluationCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCollaboratorEvaluation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $collaboratorId, public array $skills) {}

    public function handle(): void
    {
        try {
            $collaborator = Collaborator::find($this->collaboratorId);
            if (!$collaborator) {
                return; // Collaborator não encontrado, encerra o Job
            }

            // 1. Chama o Service da OpenAI
            $evaluationResult = OpenAIEvaluationService::evaluate($collaborator, $this->skills);

            // 2. Salva no histórico de avaliações
            $evaluation = CollaboratorEvaluation::create([
                'collaborator_id' => $collaborator->id,
                // O 'feedback' da OpenAI é a resposta completa
                'feedback'  => $evaluationResult['feedback'] ?? ['message' => 'N/A'],
                'opinion'   => $evaluationResult['opinion'],
                'points'    => $evaluationResult['points'],
                'positions' => $evaluationResult['positions'],
            ]);

            // 3. Dispara a notificação para o Frontend
            event(new EvaluationCompleted($collaborator, $evaluation));

        } catch (\Throwable $e) {
            // Lógica de tratamento de erro (ex: logar e notificar administradores)
            \Log::error("Evaluation Job Failed for Collaborator {$this->collaboratorId}: " . $e->getMessage());
            $this->fail($e); // Marca o Job como falho
        }
    }
}
