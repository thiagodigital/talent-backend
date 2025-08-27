<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExamDiscCollectionResource extends ResourceCollection
{
    public static function collection($categories)
    {
        // primeiro, chunk de 3 em cada categoria
        $chunkedByCategory = $categories->map(function ($category) {
            return $category->profileTraits
                // ->pluck('name') // pega só os nomes
                ->chunk(3)      // agrupa de 3 em 3
                ->map(function ($traits) use ($category) {
                    return [
                        'id'       => $category->id,
                        'category' => $category->name,
                        'color'    => $category->color,
                        'name'     => $traits->values()->pluck('name'), // array de 3 nomes
                        'ids'     => $traits->values()->pluck('id'), // array de 3 nomes
                    ];
                });
        });

        // agora, alinhar as linhas: cada linha deve ter 1 opção de cada categoria
        $lines = [];
        $maxChunks = $chunkedByCategory->map->count()->max();

        for ($i = 0; $i < $maxChunks; $i++) {
            $options = [];
            foreach ($chunkedByCategory as $chunks) {
                if (isset($chunks[$i])) {
                    $options[] = $chunks[$i];
                }
            }

            $lines[] = [
                'line'    => $i + 1,
                'options' => $options,
            ];
        }

        return collect($lines);
    }
}
