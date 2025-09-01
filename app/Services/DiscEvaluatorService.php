<?php

namespace App\Services;

use App\Models\Collaborator;
use OpenAI\Laravel\Facades\OpenAI;

class DiscEvaluatorService
{
    public static function evaluate(Collaborator $collaborator, array $payload): array
    {
        // 🔹 Recupera ou cria Thread
        if (!$collaborator->thread_id) {
            $thread = OpenAI::threads()->create();
            $collaborator->update(['thread_id' => $thread->id]);
        } else {
            $thread = (object)['id' => $collaborator->thread_id];
        }

        // 🔹 Adiciona nova mensagem com payload
        OpenAI::threads()->messages()->create($thread->id, [
            'role' => 'user',
            'content' => json_encode($payload),
        ]);

        // 🔹 Executa o Assistente fixo
        $run = OpenAI::threads()->runs()->create($thread->id, [
            'assistant_id' => config('services.openai.assistants.disc_evaluator'),
        ]);

        // 🔹 Poll até terminar
        do {
            $run = OpenAI::threads()->runs()->retrieve($thread->id, $run->id);
            sleep(1);
        } while ($run->status !== 'completed');

        // 🔹 Recupera última mensagem do assistente
        $messages = OpenAI::threads()->messages()->list($thread->id);
        $last = collect($messages->data)
            ->first(fn($m) => $m->role === 'assistant')
            ->content[0]->text->value ?? '{}';

        return json_decode($last, true);
    }
}
