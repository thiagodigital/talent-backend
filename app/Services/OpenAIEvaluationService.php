<?php

// app/Services/OpenAIEvaluationService.php

namespace App\Services;

use App\Models\Collaborator;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAIEvaluationService
{
    public static function evaluate(Collaborator $collaborator, array $skills): array
    {
        // 1. Prepara o Payload (Usando a position do collaborator, que agora tem um valor claro)
        $payload = [
            'id' => $collaborator->id,
            'name' => $collaborator->name,
            'position' => $collaborator->position,
            'skills' => $skills
        ];

        // 2. Recupera ou cria Thread (Ajustei o campo 'tread_id' para 'thread_id')
        if (!$collaborator->thread_id) {
            $thread = OpenAI::threads()->create();
            $collaborator->update(['thread_id' => $thread->id]);
        } else {
            // Garante que o objeto $thread tem o ID necessário
            $thread = (object)['id' => $collaborator->thread_id];
        }

        // 3. Adiciona nova mensagem com payload
        OpenAI::threads()->messages()->create($thread->id, [
            'role' => 'user',
            'content' => json_encode($payload),
        ]);

        // 4. Executa e espera a conclusão
        $run = OpenAI::threads()->runs()->create($thread->id, [
            'assistant_id' => config('services.openai.assistants.evaluation_assistent'),
        ]);

        do {
            $run = OpenAI::threads()->runs()->retrieve($thread->id, $run->id);
            // Em Jobs assíncronos, o ideal é usar um tempo de espera (sleep) razoável
            sleep(2);
        } while ($run->status !== 'completed' && $run->status !== 'failed'); // Adiciona checagem de falha

        if ($run->status === 'failed') {
             // Lógica para lidar com falhas do assistente
             throw new \Exception("OpenAI Assistant Run Failed: " . $run->last_error->message ?? 'Unknown error');
        }

        // 5. Recupera o resultado e decodifica o JSON
        $messages = OpenAI::threads()->messages()->list($thread->id);
        $last = collect($messages->data)
            ->first(fn($m) => $m->role === 'assistant')
            ->content[0]->text->value ?? '{}';

        // O retorno da API deve ser decodificado
        return json_decode($last, true);
    }
}
