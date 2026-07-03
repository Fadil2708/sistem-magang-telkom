<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory, HasUuid, Auditable;

    protected $fillable = [
        'application_id', 'intern_id', 'supervisor_id', 'vacancy_id',
        'actual_start_date', 'actual_end_date', 'status',
    ];

    protected $casts = [
        'actual_start_date' => 'date',
        'actual_end_date'   => 'date',
        'status'            => 'string',
    ];

    public function application(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function intern(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'intern_id');
    }

    public function supervisor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function vacancy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function logbooks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Logbook::class);
    }

    public function approvedLogbooks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Logbook::class)->where('validation_status', 'approved');
    }

    public function finalReport(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(FinalReport::class);
    }

    public function evaluation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Evaluation::class);
    }

    public function certificate(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    public function testimonial(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Testimonial::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }
}
