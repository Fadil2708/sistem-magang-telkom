<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory, HasUuid, Auditable;

    protected $fillable = [
        'internship_id', 'supervisor_id',
        'soft_skill_score', 'hard_skill_score',
        'attendance_score', 'attitude_score',
        'final_score', 'grade', 'remarks', 'evaluated_at',
    ];

    protected $casts = [
        'evaluated_at'      => 'datetime',
        'soft_skill_score'  => 'decimal:2',
        'hard_skill_score'  => 'decimal:2',
        'attendance_score'  => 'decimal:2',
        'attitude_score'    => 'decimal:2',
        'final_score'       => 'decimal:2',
        'grade'             => 'string',
    ];

    public function internship(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }

    public function supervisor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function calculateFinalScore(): void
    {
        $this->final_score = round(
            ($this->soft_skill_score  * 0.25) +
            ($this->hard_skill_score  * 0.35) +
            ($this->attendance_score  * 0.20) +
            ($this->attitude_score    * 0.20),
            2
        );

        $this->grade = match(true) {
            $this->final_score >= 85 => 'A',
            $this->final_score >= 70 => 'B',
            $this->final_score >= 55 => 'C',
            default                  => 'D',
        };
    }
}
