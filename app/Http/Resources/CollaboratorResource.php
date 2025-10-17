<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollaboratorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'email'    => $this->email,
            'position' => $this->position,
            'phone'    => $this->phone,
            'evaluations' => $this->whenLoaded('collaboratorEvaluation', function () {
                return CollaboratorEvaluationResource::collection($this->collaboratorEvaluation);
            }),
        ];
    }
}
