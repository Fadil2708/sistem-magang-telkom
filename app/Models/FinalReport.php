<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalReport extends Model
{
    use HasFactory, HasUuid, Auditable;

    protected $fillable = [
        'internship_id', 'intern_id', 'title',
        'file_url', 'file_size_kb',
        'submitted_at', 'supervisor_approval', 'approved_at',
    ];

    protected $casts = [
        'submitted_at'        => 'datetime',
        'approved_at'         => 'datetime',
        'supervisor_approval' => 'string',
        'file_size_kb'        => 'integer',
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
