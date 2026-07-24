<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use Auditable;
    protected $fillable = ['name', 'category'];

    public function internProfiles(): BelongsToMany
    {
        return $this->belongsToMany(InternProfile::class)->withTimestamps();
    }
}
