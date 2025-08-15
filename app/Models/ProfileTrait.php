<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileTrait extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'category_id',
    ];

    public function profileCategory()
    {
        return $this->belongsTo(ProfileCategory::class, 'category_id');
    }

    public function collaborators()
    {
        return $this->belongsToMany(Collaborator::class, 'collaborator_profile_trait')
                    ->withPivot('score')
                    ->withTimestamps();
    }
}
