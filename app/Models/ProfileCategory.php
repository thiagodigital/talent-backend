<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileCategory extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'color',
    ];

    public function profileTraits()
    {
        return $this->hasMany(ProfileTrait::class, 'category_id');
    }
}
