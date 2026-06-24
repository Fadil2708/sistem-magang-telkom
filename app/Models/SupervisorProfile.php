<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisorProfile extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'user_id', 'full_name', 'employee_id', 'division', 'position', 'phone',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
