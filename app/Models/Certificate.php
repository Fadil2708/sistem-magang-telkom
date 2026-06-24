<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory, HasUuid, Auditable;

    protected $fillable = [
        'internship_id', 'intern_id', 'certificate_number',
        'issued_by', 'final_score', 'grade',
        'qr_code_token', 'qr_code_url', 'certificate_file_url',
        'issued_at',
    ];

    protected $casts = [
        'issued_at'   => 'datetime',
        'final_score' => 'decimal:2',
        'grade' => 'string',
    ];

    public function internship(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }

    public function intern(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'intern_id');
    }

    public function issuedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
