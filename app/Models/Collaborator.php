<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Collaborator extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address_id',
        'position',
        'tread_id',
        'parent_id',
        'role_id',
    ];


    public function  user()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function profileEvaluations(): BelongsToMany
    {
        return $this->belongsToMany(
            ProfileEvaluation::class,
            'collaborator_profile_evaluations',
            'collaborator_id', // Chave Local (Collaborator)
            'evaluation_id'  // CHAVE REMOTA (ProfileEvaluation)
        )
        ->withPivot('type', 'value', 'position')
        ->withTimestamps();
    }

}
