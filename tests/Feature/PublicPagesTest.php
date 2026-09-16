<?php

namespace Tests\Feature;

use App\Livewire\Public\Landing;
use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role, array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => ucfirst($role).' User',
            'email' => $role.'-'.uniqid().'@kivu.test',
            'password' => Hash::make('password'),
            'role' => $role,
            'status' => 'active',
        ], $attributes));
    }

    public function test_landing_page_loads_with_seo_and_categories(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(SkillSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertSee('Bangun portofolio', false)
            ->assertSee('Desain Grafis', false)
            ->assertSee('og:title', false);
    }

    public function test_landing_category_filter_narrows_projects(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(SkillSeeder::class);

        $owner = $this->makeUser('umkm');

        Project::create([
            'owner_id' => $owner->id,
            'category_id' => Category::where('slug', 'web-it')->value('id'),
            'title' => 'Landing Page Toko',
            'description' => 'Butuh landing page.',
            'budget' => 1500000,
            'status' => 'OPEN',
        ]);

        Project::create([
            'owner_id' => $owner->id,
            'category_id' => Category::where('slug', 'desain-grafis')->value('id'),
            'title' => 'Desain Logo Toko',
            'description' => 'Butuh logo.',
            'budget' => 500000,
            'status' => 'OPEN',
        ]);

        Livewire::test(Landing::class)
            ->call('selectCategory', 'web-it')
            ->assertViewHas('projects', function ($projects) {
                $titles = $projects->pluck('title');

                return $titles->contains('Landing Page Toko') && ! $titles->contains('Desain Logo Toko');
            })
            ->call('selectCategory', 'web-it')
            ->assertSet('category', '');
    }

    public function test_talent_profile_renders_for_student(): void
    {
        $student = $this->makeUser('student', ['bio' => 'Desainer grafis.']);

        $this->get('/talents/'.$student->id)
            ->assertOk()
            ->assertSee($student->name)
            ->assertSee('Portofolio');
    }

    public function test_talent_profile_rejects_non_student(): void
    {
        $umkm = $this->makeUser('umkm');

        $this->get('/talents/'.$umkm->id)->assertNotFound();
    }

    public function test_auth_pages_render_with_noindex(): void
    {
        $this->get('/login')->assertOk()->assertSee('noindex', false);
        $this->get('/register')->assertOk()->assertSee('noindex', false);
    }
}
