<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RegistrationInvite extends Model
{
    use HasUuid, Auditable;

    protected $fillable = [
        'code', 'role', 'email', 'used_at', 'expires_at', 'created_by',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isValid(): bool
    {
        return !$this->used_at
            && (!$this->expires_at || $this->expires_at->isFuture());
    }

    public function markAsUsed(?string $email = null): void
    {
        $this->update([
            'used_at' => now(),
            'email' => $email ?? $this->email,
        ]);
    }

    public static function generate(string $role, ?string $email = null, ?\DateTimeInterface $expiresAt = null): self
    {
        return static::create([
            'code' => strtoupper(Str::random(8)),
            'role' => $role,
            'email' => $email,
            'expires_at' => $expiresAt,
            'created_by' => auth()->id(),
        ]);
    }
}
