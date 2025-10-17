<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCollaboratorRequest;
use App\Http\Requests\UpdateCollaboratorRequest;
use App\Http\Resources\CollaboratorResource;
use App\Http\Resources\CollaboratorResource2;
use App\Models\Collaborator;
use App\Traits\ApiResponse;

class CollaboratorController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parent_id = auth()->user()->id;
        $collaborators = Collaborator::where('parent_id', $parent_id)->get();
        return $this->successResponse(CollaboratorResource::collection($collaborators), "collaborators retrieved successfully.");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCollaboratorRequest $request)
    {
        $request->validated();
        $auth = auth()->user();
        $request->merge(['parent_id' => $auth->id]);
        $collaborator = Collaborator::create($request->all());
        return $this->successResponse(new CollaboratorResource($collaborator), "Collaborator created successfully.", 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Collaborator $collaborator)
    {
        $parent_id = auth()->user()->id;
        // return Collaborator::where('parent_id', $parent_id)->first();
        $item = Collaborator::where('parent_id', $parent_id)
            ->findOrFail($collaborator->id);
            // dd($item);
        return $this->successResponse(new CollaboratorResource2($item), "Collaborator retrieved successfully.");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCollaboratorRequest $request, Collaborator $collaborator)
    {
        $collaborator->update($request->validated());
        return $this->successResponse(new CollaboratorResource($collaborator), "Position updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collaborator $collaborator)
    {
        $collaborator->delete();
        return $this->successResponse(null, "Position deleted successfully.", 204);
    }
}
