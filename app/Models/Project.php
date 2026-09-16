<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['owner_id', 'category_id', 'title', 'description', 'budget', 'min_budget', 'max_budget', 'due_date', 'status'];

    protected function casts(): array
    {
        return [
            'budget' => 'integer',
            'min_budget' => 'integer',
            'max_budget' => 'integer',
            'due_date' => 'date',
        ];
    }

    public function hasBudgetRange(): bool
    {
        return $this->min_budget !== null && $this->max_budget !== null
            && $this->min_budget !== $this->max_budget;
    }

    public function agreedAmount(?Application $winner = null): int
    {
        $accepted = $winner ?? $this->applications()
            ->where('status', 'ACCEPTED')
            ->whereNotNull('bid_amount')
            ->first();

        if ($accepted && $accepted->bid_amount !== null) {
            return (int) $accepted->bid_amount;
        }

        return (int) $this->budget;
    }

    public function winnerApp(): ?Application
    {
        return $this->applications()
            ->where('status', 'ACCEPTED')
            ->first();
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function fund(): HasOne
    {
        return $this->hasOne(ProjectFund::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
