<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Collaborator extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address_id',
        'position_id',
        'parent_id',
        'role_id',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function  user()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function profileTraits()
    {
        return $this->belongsToMany(ProfileTrait::class, 'collaborator_profile_trait')
                    ->withPivot('score')
                    ->withTimestamps();
    }

}