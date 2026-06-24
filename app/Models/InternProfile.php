<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InternProfile extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'user_id', 'full_name', 'gender', 'phone', 'address', 'date_of_birth',
        'institution_name', 'institution_type', 'major', 'student_id',
        'photo_url', 'cv_url', 'cover_letter_url',
    ];

    protected $casts = [
        'date_of_birth'   => 'date',
        'institution_type' => 'string',
        'gender'          => 'string',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->withTimestamps();
    }

    public function isComplete(): bool
    {
        return !empty($this->full_name)
            && !empty($this->phone)
            && !empty($this->student_id)
            && !empty($this->institution_name);
    }
}
