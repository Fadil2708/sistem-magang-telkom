<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    protected $fillable = ['name', 'category'];

    public function internProfiles(): BelongsToMany
    {
        return $this->belongsToMany(InternProfile::class)->withTimestamps();
    }
}
