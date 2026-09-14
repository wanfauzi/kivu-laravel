<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dispute extends Model
{
    public const REASONS = [
        'work_not_as_agreed' => 'Hasil tak sesuai',
        'not_paid' => 'Tidak dibayar',
        'unresponsive' => 'Tidak responsif',
        'overdue' => 'Lewat tenggat',
        'other' => 'Lainnya',
    ];

    protected $fillable = [
        'project_id',
        'reporter_id',
        'against_id',
        'reason',
        'description',
        'status',
        'resolution',
        'resolved_by',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function against(): BelongsTo
    {
        return $this->belongsTo(User::class, 'against_id');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function reasonLabel(): string
    {
        return self::REASONS[$this->reason] ?? $this->reason;
    }
}
