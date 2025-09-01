<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CollaboratorEvaluation extends Model
{
    use HasUuids;

    protected $fillable = [
        'collaborator_id',
        'summary',
        'proficience',
        'align',
        'assets',
        'questions',
        'score',
    ];

    protected $casts = [
        'assets' => 'array',
        'questions' => 'array',
    ];

    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class);
    }
}
