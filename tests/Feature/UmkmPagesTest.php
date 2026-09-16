<?php

namespace Tests\Feature;

use App\Livewire\Umkm\CreateProject;
use App\Livewire\Umkm\EditProject;
use App\Livewire\Umkm\ManageApplicants;
use App\Livewire\Umkm\MyProjects;
use App\Livewire\Umkm\Profile;
use App\Models\Application;
use App\Models\Category;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UmkmPagesTest extends TestCase
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

    public function test_umkm_creates_project_with_category_skills_and_deadline(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(SkillSeeder::class);

        $umkm = $this->makeUser('umkm');
        $category = Category::where('slug', 'web-it')->first();
        $skill = Skill::where('slug', 'web-development')->first();

        $this->actingAs($umkm);

        Livewire::test(CreateProject::class)
            ->set('title', 'Landing Page Toko')
            ->set('description', 'Butuh landing page responsif untuk toko online dengan tampilan modern.')
            ->set('min_budget', 1000000)
            ->set('max_budget', 1500000)
            ->set('category_id', (string) $category->id)
            ->set('skillIds', [(string) $skill->id])
            ->set('due_date', now()->addDays(14)->format('Y-m-d'))
            ->call('store')
            ->assertHasNoErrors()
            ->assertRedirect();

        $project = Project::firstWhere('title', 'Landing Page Toko');

        $this->assertNotNull($project);
        $this->assertSame($category->id, $project->category_id);
        $this->assertSame('DRAFT', $project->status);
        $this->assertSame(1500000, $project->max_budget);
        $this->assertNotNull($project->due_date);
        $this->assertCount(1, $project->skills);
    }

    public function test_umkm_edit_project_updates_category_skills_and_deadline(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(SkillSeeder::class);

        $umkm = $this->makeUser('umkm');
        $project = Project::create([
            'owner_id' => $umkm->id,
            'title' => 'Desain Logo',
            'description' => 'Butuh desain logo yang sederhana, modern, dan menarik untuk bisnis.',
            'budget' => 500000,
            'status' => 'OPEN',
        ]);

        $category = Category::where('slug', 'desain-grafis')->first();
        $skill = Skill::where('slug', 'branding')->first();

        $this->actingAs($umkm);

        Livewire::test(EditProject::class, ['project' => $project])
            ->set('title', 'Desain Logo & Branding')
            ->set('category_id', (string) $category->id)
            ->set('skillIds', [(string) $skill->id])
            ->set('due_date', now()->addDays(10)->format('Y-m-d'))
            ->call('update')
            ->assertHasNoErrors();

        $project->refresh();

        $this->assertSame('Desain Logo & Branding', $project->title);
        $this->assertSame($category->id, $project->category_id);
        $this->assertNotNull($project->due_date);
        $this->assertCount(1, $project->skills);
    }

    public function test_my_projects_filters_by_status(): void
    {
        $umkm = $this->makeUser('umkm');

        Project::create(['owner_id' => $umkm->id, 'title' => 'Terbuka', 'description' => 'x', 'budget' => 200000, 'status' => 'OPEN']);
        Project::create(['owner_id' => $umkm->id, 'title' => 'Selesai', 'description' => 'x', 'budget' => 300000, 'status' => 'COMPLETED']);

        $this->actingAs($umkm);

        Livewire::test(MyProjects::class)
            ->call('setStatus', 'COMPLETED')
            ->assertViewHas('projects', fn ($projects) => $projects->pluck('title')->contains('Selesai') && ! $projects->pluck('title')->contains('Terbuka'))
            ->call('setStatus', 'COMPLETED')
            ->assertSet('statusFilter', '');
    }

    public function test_rejecting_applicant_saves_note_and_notifies(): void
    {
        $umkm = $this->makeUser('umkm');
        $student = $this->makeUser('student');

        $project = Project::create([
            'owner_id' => $umkm->id,
            'title' => 'Foto Produk',
            'description' => 'Butuh foto.',
            'budget' => 600000,
            'status' => 'OPEN',
        ]);

        $application = Application::create([
            'project_id' => $project->id,
            'student_id' => $student->id,
            'status' => 'PENDING',
        ]);

        $this->actingAs($umkm);

        Livewire::test(ManageApplicants::class, ['project' => $project])
            ->call('confirmReject', $application->id)
            ->set('rejection_note', 'Portofolio belum sesuai.')
            ->call('reject')
            ->assertHasNoErrors();

        $application->refresh();

        $this->assertSame('REJECTED', $application->status);
        $this->assertSame('Portofolio belum sesuai.', $application->rejection_note);
        $this->assertSame(1, $student->refresh()->notifications()->count());
    }

    public function test_non_owner_cannot_manage_applicants(): void
    {
        $owner = $this->makeUser('umkm');
        $other = $this->makeUser('umkm');

        $project = Project::create([
            'owner_id' => $owner->id,
            'title' => 'Proyek Privat',
            'description' => 'x',
            'budget' => 400000,
            'status' => 'OPEN',
        ]);

        $this->actingAs($other)
            ->get(route('umkm.manage-applicants', $project->id))
            ->assertForbidden();
    }

    public function test_umkm_can_update_business_name(): void
    {
        $umkm = $this->makeUser('umkm', ['business_name' => 'Toko Lama']);

        $this->actingAs($umkm);

        Livewire::test(Profile::class)
            ->set('business_name', 'Toko Baru')
            ->set('name', $umkm->name)
            ->call('updateProfile')
            ->assertHasNoErrors();

        $this->assertSame('Toko Baru', $umkm->refresh()->business_name);
    }
}
