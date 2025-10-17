<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollaboratorResource2 extends JsonResource
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
            // 'profile_category' => $profileCategories,
            'evaluations' => $this->whenLoaded('evaluations', function () {
                return $this->evaluations->map(fn($eval) => [
                    'id'         => $eval->id,
                    // 'summary'    => $eval->summary,
                    'proficience'=> $eval->proficience,
                    'align'      => $eval->align,
                    'assets'     => $eval->assets,
                    'questions'  => $eval->questions,
                    'score'      => $eval->score,
                    'created_at' => $eval->created_at->toDateTimeString(),
                ]);
            }),
        ];
    }
}
