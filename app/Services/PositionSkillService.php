<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use JsonSchema\Validator;
use JsonSchema\Constraints\Constraint;

class PositionSkillService
{
    public function make(string $position)
    {
        $prompt = $this->buildPrompt($position);

        $response = Http::withToken(env('OPENAI_API_KEY'))->post(env('OPENAI_API_KEY').'/chat/completions', [
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'Você é um assistente que responde apenas com JSON conforme o schema.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.5
        ]);

        $jsonContent = json_decode($response->json()['choices'][0]['message']['content'] ?? '{}', true);

        // Validar JSON
        if (!$this->isValidJson($jsonContent)) {
            return ['error' => 'Resposta inválida do modelo.'];
        }

        return $jsonContent;
    }

    private function buildPrompt(string $position): string
    {
        return <<<EOT
Considere o position "$position".

Com base em seu conhecimento das soft skills e hard skills ideais, retorne as mais adequadas para esse position no seguinte formato JSON:

{
  "position": "string",
  "softskills": ["string", "string", ...],
  "hardskills": ["string", "string", ...]
}

A resposta deve ser um JSON válido.
EOT;
    }

    private function isValidJson(array $data): bool
    {
        $validator = new Validator();

        $schema = json_decode(json_encode([
            'type' => 'object',
            'properties' => [
                'position' => ['type' => 'string'],
                'softskills' => [
                    'type' => 'array',
                    'items' => ['type' => 'string']
                ],
                'hardskills' => [
                    'type' => 'array',
                    'items' => ['type' => 'string']
                ]
            ],
            'required' => ['position', 'softskills', 'hardskills']
        ]));

        $validator->validate($data, $schema, Constraint::CHECK_MODE_APPLY_DEFAULTS);

        return $validator->isValid();
    }
}
