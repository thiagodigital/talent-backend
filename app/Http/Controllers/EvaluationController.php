<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEvaluationRequest;
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

    public function storeEvaluation(StoreEvaluationRequest $request)
    {
        $request->validated();

        $collaborator = Collaborator::findOrFail($request->collaborator_id);

        $response_skills = [];
        $response_skills['collaborator_id'] = $collaborator->id;
        $response_skills['thread_id'] = $collaborator->thread_id;
        $response_skills['name'] = $collaborator->name;
        $response_skills['position'] = $collaborator->position;
        $response_skills['hardskills'] = collect($request->skills)->where('type', 'hard skill')->map(function ($skill) {
            if ($skill['type'] == 'hard skill') {
                return $skill;
            }
        });
        $response_skills['softskills'] = collect($request->skills)->where('type', 'soft skill')->map(function ($skill) {
            if ($skill['type'] == 'soft skill') {
                return $skill;
            }
        });


        // 5. Despacha o Job para processamento assíncrono
        ProcessCollaboratorEvaluation::dispatch($response_skills);

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
