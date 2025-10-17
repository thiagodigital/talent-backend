<?php

namespace Database\Factories;

use App\Imports\EvaluationImport;
use Illuminate\Database\Eloquent\Factories\Factory;
use Maatwebsite\Excel\Facades\Excel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evaluation>
 */
class EvaluationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        Excel::import(new EvaluationImport, resource_path('assets/evaluation.csv'));
        return [
            //
        ];
    }
}
