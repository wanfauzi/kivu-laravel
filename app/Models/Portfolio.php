<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    protected $fillable = ['student_id', 'title', 'description', 'url', 'file_path'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function isImage(): bool
    {
        if (!$this->file_path) {
            return false;
        }

        $ext = strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));

        return in_array($ext, ['jpg', 'jpeg', 'png'], true);
    }
}
