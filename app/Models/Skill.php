<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    private const SLUG_OVERRIDES = [
        'UI/UX' => 'ui-ux',
    ];

    public static function findOrCreateByName(string $name): Skill
    {
        $name = trim($name);
        $slug = self::SLUG_OVERRIDES[$name] ?? Str::slug($name, '-');

        return self::firstOrCreate(['name' => $name], ['slug' => $slug]);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }
}
