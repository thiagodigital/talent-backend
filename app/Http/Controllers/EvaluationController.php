<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileEvaluationRequest;
use App\Http\Resources\EvaluationResourceCollection;
use App\Jobs\ProcessCollaboratorEvaluation;
use App\Jobs\ProcessExamEvaluationJob;
use App\Models\Collaborator;
use App\Models\Evaluation;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    use ApiResponse;

    public function evaluationList()
    {
        $evaluations = Evaluation::all();
        // return $evaluations;

        return $this->successResponse(EvaluationResourceCollection::collection($evaluations), "Evaluations retrieved successfully.");
    }

    public function storeEvaluation(ProfileEvaluationRequest $request)
    {
        $request->validated();

        $collaborator = Collaborator::findOrFail($request->collaborator_id);
        $collaboratorPosition = $collaborator->position;

        // 1. Mapeia e prepara os dados para o sync E para o Job.
        // Aqui está a correção final para evitar "Undefined array key 'position'"
        $allSkills = collect($request->skills)->map(function ($skill) use ($collaboratorPosition) {
            // $skill['position'] = $collaboratorPosition; // Garante que a chave exista
            return $skill;
        });
        // dd($allSkills);

        // 2. Prepara o Array para o sync() (Apenas dados PIVOT)
        $attachData = $allSkills->mapWithKeys(function ($skill) {
            return [
                $skill['id'] => [
                    'type' => $skill['type'],
                    'value' => $skill['value'],
                    // 'position' => $skill['position'],
                    ]
                ];
            })
            ->all();

            // 3. Sincroniza (salva no DB)
            // Isso resolve todos os erros de relacionamento (DB)
            // $collaborator->profileEvaluations()->sync($syncData);
            $collaborator->profileEvaluations()->detach();

            // 4. ANEXA os novos registros em um loop.
            foreach ($attachData as $evaluationId => $pivotData) {
                // Usa o attach() para criar um novo registro na tabela pivot
                $collaborator->profileEvaluations()->attach($evaluationId, $pivotData);
            }
            // dd($syncData);

        // 4. Prepara o Array para o Job (Apenas os arrays de skills limpos)
        $jobSkills = $allSkills->all();

        // 5. Despacha o Job para processamento assíncrono
        ProcessCollaboratorEvaluation::dispatch($collaborator->id, $jobSkills);

        // 6. Retorno Imediato (HTTP 202 - Accepted)
        // Isso informa ao frontend que a requisição foi aceita e será processada.
        return $this->successResponse(
            ['collaborator_id' => $collaborator->id],
            "Evaluation processing started. Check for updates.",
            202
        );
    }

    public function show(Evaluation $evaluation)
    {
        //
    }

    public function edit(Evaluation $evaluation)
    {
        //
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        //
    }

    public function destroy(Evaluation $evaluation)
    {
        //
    }
}
