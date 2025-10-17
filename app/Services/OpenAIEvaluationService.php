<?php

// app/Services/OpenAIEvaluationService.php

namespace App\Services;

use App\Models\Collaborator;
use Illuminate\Database\Eloquent\Collection;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAIEvaluationService
{
    public static function evaluate(array $response_skills): array
    {
        // 1. Prepara o Payload (Usando a position do collaborator, que agora tem um valor claro)
        $payload = [
            'id' => $response_skills['collaborator_id'],
            'name' => $response_skills['name'],
            'position' => $response_skills['position'],
            'hardskills' => $response_skills['hardskills'],
            'softskills' => $response_skills['softskills'],
        ];
        // 2. Recupera ou cria Thread (Ajustei o campo 'tread_id' para 'thread_id')
        if (!$response_skills['thread_id']) {
            $thread = OpenAI::threads()->create();
            Collaborator::find($response_skills['collaborator_id'])->update(['thread_id' => $thread->id]);
            $response_skills['thread_id'] = $thread->id;
        } else {
            // Garante que o objeto $thread tem o ID necessário
            $thread = (object)['id' => $response_skills['thread_id']];
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
