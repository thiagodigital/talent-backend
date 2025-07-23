<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use App\Traits\ApiResponse;

class PositionController extends Controller
{
    use ApiResponse;
     // GET /api/positions
    public function index()
    {
        $positions = Position::all();
        return $this->successResponse(PositionResource::collection($positions), "Positions retrieved successfully.");
    }

    // POST /api/positions
    public function store(StorePositionRequest $request)
    {
        $position = Position::create($request->validated());
        return $this->successResponse(new PositionResource($position), "Position created successfully.", 201);
    }

    // GET /api/positions/{id}
    public function show(Position $position)
    {
        return $this->successResponse(new PositionResource($position), "Position retrieved successfully.");
    }

    // PUT /api/positions/{id}
    public function update(UpdatePositionRequest $request, Position $position)
    {
        $position->update($request->validated());
        return $this->successResponse(new PositionResource($position), "Position updated successfully.");
    }

    // DELETE /api/positions/{id}
    public function destroy(Position $position)
    {
        $position->delete();
        return $this->successResponse(null, "Position deleted successfully.", 204);
    }
}
