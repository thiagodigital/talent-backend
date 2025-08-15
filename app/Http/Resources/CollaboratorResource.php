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
        // garante que temos uma Collection
        $traits = $this->relationLoaded('profileTraits')
            ? collect($this->profileTraits)
            : collect();

        // agrupa por categoria (ID)
        $grouped = $traits->groupBy(fn($trait) => $trait->profileCategory->id ?? null);

        // transforma no formato desejado
        $profile = $grouped->map(function ($items) {
            $category = $items->first()->profileCategory ?? null;

            return [
                    'id' => $category?->id,
                    'name' => $category?->name,
                    'traits' => $items->map(function ($trait) {
                        return [
                            'id' => $trait->id,
                            'name' => $trait->name,
                            'score' => $trait->pivot->score ?? 0,
                        ];
                    })->values(),
            ];
        })->values();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'profile_category' => $this->when($profile->isNotEmpty(), $profile),
        ];
    }
}
