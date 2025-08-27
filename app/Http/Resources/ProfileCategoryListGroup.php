<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileCategoryListGroup extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                'id'     => $this?->id,
                'name'   => $this?->name,
                'color'  => $this?->color,
                'traits' => $this->profileTraits
                    ->map(fn($trait) => [
                        'id'   => $trait->id,
                        'name' => $trait->name,
                    ])
                    ->values()
                    ->chunk(3) // 👈 aqui é a mágica → divide em grupos de 3
                    ->values()
                    ->toArray(),
            ];


        // // garante que temos uma Collection
        // $traits = $this->relationLoaded('profileTraits')
        //     ? collect($this->profileTraits)
        //     : collect();

        // // agrupa por categoria
        // $grouped = $traits->groupBy(fn($trait) => $trait->profileCategory->id ?? null);

        // // monta resposta
        // $profile = $grouped->map(function ($items) {
        //     $category = $items->first()->profileCategory ?? null;

        //     return [
        //         'id'     => $category?->id,
        //         'name'   => $category?->name,
        //         'color'  => $category?->color,
        //         'traits' => $items
        //             ->map(fn($trait) => [
        //                 'id'   => $trait->id,
        //                 'name' => $trait->name,
        //             ])
        //             ->values()
        //             ->chunk(3) // 👈 aqui é a mágica → divide em grupos de 3
        //             ->values(),
        //     ];
        // })->values();

        // return $profile->toArray();
    }
}
