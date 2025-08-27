<?php

namespace App\Jobs;

use App\Models\Collaborator;
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
            'traits' => $collaborator->profileTraits->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'score' => $t->pivot->score,
                'category' => $t->profileCategory->name,
            ])->toArray(),
        ];

        // Chama API do GPT
        $client = OpenAI::client(config('services.openai.key'));

        $response = $client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'Você é um avaliador DISC. Responda sempre em JSON.'],
                ['role' => 'user', 'content' => json_encode($payload)]
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => [
                    'name' => 'disc_evaluation',
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'dominant' => ['type' => 'string'],
                            'influente' => ['type' => 'string'],
                            'estavel' => ['type' => 'string'],
                            'analista' => ['type' => 'string'],
                            'summary' => ['type' => 'string'],
                        ],
                        'required' => ['dominant','influente','estavel','analista','summary']
                    ]
                ]
            ]
        ]);

        $evaluation = json_decode($response->choices[0]->message->content, true);

        // Salva resultado no banco
        CollaboratorEvaluation::updateOrCreate(
            ['collaborator_id' => $collaborator->id],
            ['result' => $evaluation]
        );
    }
}
