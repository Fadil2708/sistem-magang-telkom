<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory, HasUuid, Auditable, SoftDeletes;

    protected $fillable = [
        'intern_id', 'internship_id', 'rating', 'content', 'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'rating' => 'integer',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function intern(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'intern_id');
    }

    public function internship(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }
}
