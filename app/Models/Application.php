<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory, HasUuid, Auditable;

    protected $fillable = [
        'intern_id', 'vacancy_id', 'status',
        'interview_date', 'rejection_reason', 'admin_notes',
        'applied_at',
    ];

    protected $casts = [
        'interview_date' => 'datetime',
        'applied_at'     => 'datetime',
        'status'         => 'string',
    ];

    public function intern(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'intern_id');
    }

    public function vacancy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function internship(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Internship::class);
    }
}
