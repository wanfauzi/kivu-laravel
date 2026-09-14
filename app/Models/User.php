<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'status', 'status_before_suspend', 'student_verified_at', 'ktm_path', 'business_name', 'bio', 'skills'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'student_verified_at' => 'datetime',
            'skills' => 'array',
            'password' => 'hashed',
        ];
    }

    public function isVerifiedStudent(): bool
    {
        return $this->role === 'student' && $this->student_verified_at !== null;
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class, 'student_id')->latest();
    }

    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewee_id')->latest();
    }
}
