<?php

namespace App\Jobs;

use App\Models\Collaborator;
use Illuminate\Bus\Queueable;
use App\Events\EvaluationCompleted;
use App\Models\CollaboratorEvaluation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\OpenAIEvaluationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ProcessCollaboratorEvaluation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Collection | array $response_data) {}

    public function handle(): void
    {
        try {
            $collaborator = Collaborator::find($this->response_data['collaborator_id']);
            if (!$collaborator) {
                return; // Collaborator não encontrado, encerra o Job
            }

            // 1. Chama o Service da OpenAI
            $evaluationResult = OpenAIEvaluationService::evaluate($this->response_data);

            // 2. Salva no histórico de avaliações
            $evaluation = CollaboratorEvaluation::create([
                'collaborator_id' => $collaborator->id,
                // O 'feedback' da OpenAI é a resposta completa
                'feedback'  => $evaluationResult['feedback'] ?? ['message' => 'N/A'],
                'opinion'   => $evaluationResult['opinion'],
                'points'    => $evaluationResult['points'],
                'positions' => $evaluationResult['positions'],
            ]);

            event(new EvaluationCompleted($collaborator, $evaluation));

        } catch (\Throwable $e){
            Log::error("Evaluation Job Failed for Collaborator {$this->response_data['collaborator_id']}: " . $e->getMessage());
            $this->fail($e); // Marca o Job como falho
        }
    }
}
