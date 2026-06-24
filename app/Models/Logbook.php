<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    use HasFactory, HasUuid, Auditable;

    protected $fillable = [
        'internship_id', 'intern_id', 'activity_date',
        'activities', 'output', 'validation_status',
        'supervisor_notes', 'reviewed_at',
    ];

    protected $casts = [
        'activity_date'     => 'date',
        'reviewed_at'       => 'datetime',
        'validation_status' => 'string',
    ];

    public function internship(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }

    public function intern(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'intern_id');
    }
}
