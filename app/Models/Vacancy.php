<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory, HasUuid, Auditable;

    protected $fillable = [
        'created_by', 'title', 'division', 'description', 'qualifications',
        'quota', 'start_date', 'end_date', 'application_deadline', 'status',
    ];

    protected $casts = [
        'start_date'           => 'date',
        'end_date'             => 'date',
        'application_deadline' => 'date',
        'status'               => 'string',
        'quota'                => 'integer',
    ];

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function isOpen(): bool { return $this->status === 'open'; }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', 'open');
    }

    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('status', 'closed');
    }

    public static function autoCloseExpired(): int
    {
        return static::where('status', 'open')
            ->whereDate('end_date', '<', now())
            ->update(['status' => 'closed']);
    }
}
