<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollaboratorEvaluation extends Model
{
    protected $fillable = [
        'collaborator_id',
        'feedback',
        'opinion',
        'points',
        'positions',
    ];

    protected $casts = [
        'points' => 'array',
        'positions' => 'array',
    ];

    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class);
    }
}
