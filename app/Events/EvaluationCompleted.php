<?php

namespace App\Events;

use App\Models\Collaborator;
use App\Models\CollaboratorEvaluation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EvaluationCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Collaborator $collaborator;
    public CollaboratorEvaluation $evaluation;

    public function __construct(Collaborator $collaborator, CollaboratorEvaluation $evaluation)
    {
        $this->collaborator = $collaborator;
        $this->evaluation = $evaluation;
    }

    public function broadcastOn(): Channel
    {
        // Define o canal privado para notificar apenas o usuário relevante
        // Supondo que o frontend está ouvindo um canal do usuário/empresa.
        // Adapte o nome do canal conforme sua arquitetura de broadcasting.
        return new Channel('collaborators.' . $this->collaborator->id);
    }

    public function broadcastAs(): string
    {
        return 'evaluation.completed';
    }
}
