<?php

namespace App\Imports;

use App\Models\Evaluation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EvaluationImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Evaluation::create([
                'label' => $row['label'],
                'type' => $row['type'],
            ]);
        }
    }
}
