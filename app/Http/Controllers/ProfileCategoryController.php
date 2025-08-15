<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileCategoryResource;
use App\Http\Resources\ProfileCategoryResourceCollection;
use App\Models\ProfileCategory;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProfileCategoryController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $categories = ProfileCategory::with('profileTraits')->get();
        return $this->successResponse(ProfileCategoryResourceCollection::collection($categories), "Profile Categories retrieved successfully.");
    }

    public function show(ProfileCategory $profileCategory)
    {
        $profileCategory->load('profileTraits');
        return $this->successResponse(new ProfileCategoryResource($profileCategory), "Profile Category retrieved successfully.");
    }
}
