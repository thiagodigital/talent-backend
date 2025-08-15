<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileTraitRequest;
use App\Http\Resources\ProfileTraitResource;
use App\Http\Resources\ProfileTraitResourceCollection;
use App\Models\Collaborator;
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
            $novoarray[$key] = ['collaborator_id' => $request->collaborator_id, 'profile_trait_id'=> $trait['id'],'score' => $countarray[array_rand([0,25,50,75,100], 1)]];
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
    // {
    //     $profileCategory->profileTraits;
    //     return $this->successResponse(new CollaboratorResource($collaborator), "Collaborator retrieved successfully.");
    // }

    /**
     * Update the specified resource in storage.
     */
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
