<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollaboratorEvaluationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'feedback' => $this->feedback,
            'opinion' => $this->type,
            'points' => $this->points,
            'positions' => $this->positions,
        ];
    }
}
