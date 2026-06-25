<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use MustVerifyEmailTrait;
    use HasApiTokens, HasFactory, HasUuid, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'role',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    public function internProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(InternProfile::class, 'user_id');
    }

    public function supervisorProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SupervisorProfile::class, 'user_id');
    }

    public function vacancies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Vacancy::class, 'created_by');
    }

    public function applications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Application::class, 'intern_id');
    }

    public function supervisedInternships(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Internship::class, 'supervisor_id');
    }

    public function internships(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Internship::class, 'intern_id');
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \App\Notifications\VerifyEmail);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPassword($token));
    }

    public function delete(): ?bool
    {
        if ($this->isIntern()) {
            foreach ($this->internships as $internship) {
                $internship->certificate()?->delete();
                $internship->delete();
            }
        }

        return parent::delete();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSupervisor(): bool
    {
        return $this->role === 'supervisor';
    }

    public function isIntern(): bool
    {
        return $this->role === 'intern';
    }

    public function displayName(): string
    {
        return match ($this->role) {
            'intern' => $this->internProfile?->full_name ?? $this->email,
            'supervisor' => $this->supervisorProfile?->full_name ?? $this->email,
            default => $this->email,
        };
    }

    public function institutionOrDivision(): string
    {
        return match ($this->role) {
            'intern' => $this->internProfile?->institution_name ?? '—',
            'supervisor' => 'Pembimbing',
            default => '—',
        };
    }
}
