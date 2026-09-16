<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'withdrawal_id', 'student_id', 'amount', 'type', 'status', 'payment_method', 'payment_reference'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function withdrawal(): BelongsTo
    {
        return $this->belongsTo(Withdrawal::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Whether this transaction adds to ("in") or subtracts from ("out") the student wallet.
     */
    public function direction(): string
    {
        return match (true) {
            $this->type === 'payment' && $this->status === 'SUCCESS' => 'in',
            $this->type === 'withdrawal' && $this->status === 'REJECTED' => 'in',
            default => 'out',
        };
    }

    public function displayLabel(): string
    {
        return match (true) {
            $this->type === 'payment' => $this->project->title ?? 'Pembayaran Proyek',
            $this->type === 'refund' => 'Pengembalian Dana (Sengketa)',
            $this->type === 'withdrawal' && $this->status === 'REJECTED' => 'Penarikan Dibatalkan',
            default => 'Penarikan Saldo',
        };
    }
}
