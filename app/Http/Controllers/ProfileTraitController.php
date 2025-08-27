<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileTraitRequest;
use App\Http\Resources\ExamDiscCollectionResource;
use App\Http\Resources\ExamDiscResource;
use App\Http\Resources\ProfileCategoryListGroup;
use App\Http\Resources\ProfileTraitResource;
use App\Http\Resources\ProfileTraitResourceCollection;
use App\Models\Collaborator;
use App\Models\ProfileCategory;
use App\Models\ProfileTrait;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProfileTraitController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $traits = ProfileTrait::all();
        return $this->successResponse(ProfileTraitResourceCollection::collection($traits), "Traits retrieved successfully.");
    }

    public function store(StoreProfileTraitRequest $request)
    {
        $request->validated();
        // $auth = auth()->user();
        // $request->merge(['parent_id' => $auth->id]);
        $collaborator = Collaborator::findOrFail($request->collaborator_id);

        $novoarray = [];
        $countarray = [0,25,50,75,100];
        foreach ($request->traits as $key => $trait) {
            $novoarray[$key] = [
                'collaborator_id' => $request->collaborator_id,
                'profile_trait_id'=> $trait['id'],
                'score' => $countarray[array_rand([0,25,50,75,100], 1)]];
        }

        $collaborator->profileTraits()->sync($novoarray);
        // dd($collaborator);

        return $this->successResponse($collaborator->load('profileTraits.profileCategory'), "Traits created successfully.", 201);
    }

    public function show(ProfileTrait $profileTrait)
    {
        $profileTrait->profileCategory;
        return $this->successResponse(new ProfileTraitResource($profileTrait), "Profile trait retrieved successfully.");
    }

    public function listGroup()
    {
        $traits = ProfileCategory::with('profileTraits')->get();
        return $this->successResponse(ProfileCategoryListGroup::collection($traits), "Traits retrieved successfully.");
    }

    public function discList()
    {
        $traits = ProfileCategory::with('profileTraits')->get();
        // dd($traits);
        return $this->successResponse(ExamDiscCollectionResource::collection($traits), "Disc traits retrieved successfully.");
    }
    public function discStore(Request $request)
    {
        $request->validate([
            'collaborator_id' => 'required|exists:collaborators,id',
            'traits' => 'required|array',
            'traits.*.id' => 'required|exists:profile_traits,id',
            'traits.*.score' => 'required|integer|min:0|max:100',
        ]);

        $collaborator = Collaborator::findOrFail($request->collaborator_id);

        $novoarray = [];
        foreach ($request->traits as $key => $trait) {
            $novoarray[$key] = [
                'collaborator_id' => $request->collaborator_id,
                'profile_trait_id'=> $trait['id'],
                'score' => $trait['score']];
        }

        $collaborator->profileTraits()->sync($novoarray);
        // dd($collaborator);

        return $this->successResponse($collaborator->load('profileTraits.profileCategory'), "Disc traits stored successfully.", 201);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
