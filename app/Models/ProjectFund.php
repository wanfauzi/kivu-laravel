<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProjectFund extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'umkm_id',
        'amount',
        'reference',
        'status',
        'funded_at',
        'released_at',
        'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'funded_at' => 'datetime',
            'released_at' => 'datetime',
            'refunded_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(User::class, 'umkm_id');
    }
}
