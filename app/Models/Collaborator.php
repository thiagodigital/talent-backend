<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Collaborator extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'job',
        'thread_id',
        'parent_id',
        'role_id',
    ];


    public function  user()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function collaboratorEvaluation(): HasMany
    {
        return $this->hasMany(CollaboratorEvaluation::class);
    }

}
