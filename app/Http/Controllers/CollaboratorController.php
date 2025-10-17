<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCollaboratorRequest;
use App\Http\Requests\UpdateCollaboratorRequest;
use App\Http\Resources\CollaboratorResource;
use App\Models\Collaborator;
use App\Models\User;
use App\Traits\ApiResponse;

class CollaboratorController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $parent_id = auth()->user()->id;
        $collaborators = Collaborator::where('parent_id', $parent_id)->get();
        return $this->successResponse(CollaboratorResource::collection($collaborators), "collaborators retrieved successfully.");
    }

    public function store(StoreCollaboratorRequest $request)
    {
        $request->validated();
        $auth = auth()->user();
        $request->merge(['parent_id' => $auth->id]);
        $collaborator = Collaborator::create($request->all());
        return $this->successResponse(new CollaboratorResource($collaborator), "Collaborator created successfully.", 201);
    }

    public function show(Collaborator $collaborator)
    {
        $parent_id = auth()->user()->id;
        // return Collaborator::where('parent_id', $parent_id)->first();
        $item = Collaborator::where('parent_id', $parent_id)
            ->findOrFail($collaborator->id)->load('collaboratorEvaluation');
            // dd($item);
        return $this->successResponse(new CollaboratorResource($item), "Collaborator retrieved successfully.");
    }

    // public function update(UpdateCollaboratorRequest $request, Collaborator $collaborator)
    // {
    //     $collaborator->update($request->validated());
    //     return $this->successResponse(new CollaboratorResource($collaborator), "Position updated successfully.");
    // }

    // public function destroy(Collaborator $collaborator)
    // {
    //     $collaborator->delete();
    //     return $this->successResponse(null, "Position deleted successfully.", 204);
    // }
}
