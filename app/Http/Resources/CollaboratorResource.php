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
        // Garante que temos os traits carregados do colaborador
        $traits = $this->relationLoaded('profileTraits')
            ? collect($this->profileTraits)
            : collect();

        // Carrega todas as categorias com seus traits
        $categories = \App\Models\ProfileCategory::with('profileTraits')->get();

        $profileCategories = $categories->map(function ($category) use ($traits) {
            $mediaScore = round(
                $traits
                    ->filter(fn($t) => $t->profileCategory?->id === $category->id)
                    ->avg(fn($t) => $t->pivot->score ?? 0),
                2
            );
            return [
                'id'       => $category->id,
                'category' => $category->name,
                'color'    => $category->color,
                'media_score'=> $mediaScore,
                'options'  => $category->profileTraits
                    ->chunk(3) // agrupa de 3 em 3
                    ->map(function ($chunk) use ($traits) {
                        // Pega o primeiro trait do chunk
                        $first = $chunk->first();

                        // Busca o score do colaborador nesse trait
                        $score = optional(
                            $traits->firstWhere('id', $first->id)
                        )->pivot->score ?? null;

                        return [
                            'score' => $score,
                            'name'  => $chunk->pluck('name')->values(),
                            'ids'   => $chunk->pluck('id')->values(),
                        ];
                    })
                    ->values(),
            ];
        })->values();

        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'email'    => $this->email,
            'position' => $this->position,
            'phone'    => $this->phone,
            'profile_category' => $profileCategories,
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
