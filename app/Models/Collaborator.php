<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address_id',
        'position_id',
        'role_id',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function  user()
    {
        return $this->belongsTo(User::class);
    }
}