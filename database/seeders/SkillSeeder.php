<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    public const SKILLS = [
        'Desain Grafis',
        'Branding',
        'UI/UX',
        'Web Development',
        'Laravel',
        'Content Writing',
        'Copywriting',
        'SEO',
        'Video Editing',
        'Motion Graphics',
        'Fotografi',
        'Editing Foto',
        'Social Media',
        'Data Entry',
        'Penerjemahan',
        'Digital Marketing',
    ];

    /** Slug overrides when Str::slug() would produce an unexpected value. */
    private const SLUG_OVERRIDES = [
        'UI/UX' => 'ui-ux',
    ];

    public function run(): void
    {
        $slugs = [];

        foreach (self::SKILLS as $name) {
            $slug = self::SLUG_OVERRIDES[$name] ?? Str::slug($name);
            $slugs[] = $slug;

            Skill::updateOrCreate(['slug' => $slug], ['name' => $name]);
        }

        Skill::whereNotIn('slug', $slugs)->delete();
    }
}
