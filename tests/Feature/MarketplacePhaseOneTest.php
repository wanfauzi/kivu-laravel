<?php

namespace Tests\Feature;

use App\Livewire\Messages\Inbox;
use App\Livewire\Student\ProjectDetail;
use App\Livewire\Umkm\ManageApplicants;
use App\Models\Application;
use App\Models\Category;
use App\Models\Message;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class MarketplacePhaseOneTest extends TestCase
{
    use RefreshDatabase;

    private function seedCatalog(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(SkillSeeder::class);
    }

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

    public function test_catalog_seeders_create_ten_categories_and_skills(): void
    {
        $this->seedCatalog();

        $this->assertSame(10, Category::count());
        $this->assertSame(16, Skill::count());
        $this->assertDatabaseHas('skills', ['slug' => 'ui-ux']);
        $this->assertDatabaseHas('categories', ['slug' => 'desain-grafis']);
    }

    public function test_project_stores_category_skills_and_deadline(): void
    {
        $this->seedCatalog();

        $owner = $this->makeUser('umkm');
        $project = Project::create([
            'owner_id' => $owner->id,
            'category_id' => Category::where('slug', 'web-it')->value('id'),
            'title' => 'Landing Page UMKM',
            'description' => 'Butuh landing page.',
            'budget' => 1200000,
            'due_date' => now()->addDays(10),
            'status' => 'OPEN',
        ]);

        $project->skills()->sync([Skill::where('slug', 'web-development')->value('id')]);

        $this->assertTrue($project->category->slug === 'web-it');
        $this->assertCount(1, $project->fresh()->skills);
        $this->assertNotNull($project->fresh()->due_date);
    }

    public function test_applying_notifies_project_owner(): void
    {
        $owner = $this->makeUser('umkm');
        $student = $this->makeUser('student');

        $project = Project::create([
            'owner_id' => $owner->id,
            'title' => 'Desain Logo',
            'description' => 'Butuh logo.',
            'budget' => 500000,
            'status' => 'OPEN',
        ]);

        $this->actingAs($student);

        $file = \Illuminate\Http\UploadedFile::fake()->create('lamaran.pdf', 100, 'application/pdf');

        Livewire::test(ProjectDetail::class, ['project' => $project])
            ->set('message', 'Saya tertarik.')
            ->set('bid_amount', 300000)
            ->set('lamaranFile', $file)
            ->call('apply')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('applications', [
            'project_id' => $project->id,
            'student_id' => $student->id,
            'status' => 'PENDING',
        ]);

        $this->assertSame(1, $owner->refresh()->notifications()->count());
    }

    public function test_pending_student_can_submit_work_directly(): void
    {
        $owner = $this->makeUser('umkm');
        $student = $this->makeUser('student');

        $project = Project::create([
            'owner_id' => $owner->id,
            'title' => 'Artikel Kuliner',
            'description' => 'Butuh artikel.',
            'budget' => 300000,
            'status' => 'OPEN',
        ]);

        Application::create([
            'project_id' => $project->id,
            'student_id' => $student->id,
            'status' => 'PENDING',
        ]);

        $this->actingAs($student);

        \Livewire\Livewire::test(\App\Livewire\Student\SubmitWork::class, ['project' => $project])
            ->set('link', 'https://drive.google.com/hasil')
            ->call('confirmSubmit')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertSame('OPEN', $project->fresh()->status);
        $this->assertDatabaseHas('submissions', [
            'project_id' => $project->id,
            'student_id' => $student->id,
            'status' => 'SUBMITTED',
        ]);
    }

    public function test_participant_can_send_and_read_thread_messages(): void
    {
        $owner = $this->makeUser('umkm');
        $student = $this->makeUser('student');

        $project = Project::create([
            'owner_id' => $owner->id,
            'title' => 'Video Promosi',
            'description' => 'Butuh video.',
            'budget' => 800000,
            'status' => 'OPEN',
        ]);

        Application::create([
            'project_id' => $project->id,
            'student_id' => $student->id,
            'status' => 'PENDING',
        ]);

        $this->actingAs($student);

        Livewire::test(Inbox::class)
            ->set('projectId', $project->id)
            ->set('body', 'Halo, saya melamar proyek ini.')
            ->call('send')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('messages', [
            'project_id' => $project->id,
            'sender_id' => $student->id,
            'body' => 'Halo, saya melamar proyek ini.',
        ]);

        $message = Message::firstWhere('project_id', $project->id);

        // Owner reads the thread.
        $this->actingAs($owner);

        Livewire::test(Inbox::class)->call('selectThread', $project->id);

        $this->assertNotNull($message->fresh()->read_at);
    }

    public function test_inbox_excludes_projects_user_is_not_part_of(): void
    {
        $owner = $this->makeUser('umkm');
        $insider = $this->makeUser('student');
        $outsider = $this->makeUser('student');

        $project = Project::create([
            'owner_id' => $owner->id,
            'title' => 'Foto Produk',
            'description' => 'Butuh foto.',
            'budget' => 600000,
            'status' => 'OPEN',
        ]);

        Application::create([
            'project_id' => $project->id,
            'student_id' => $insider->id,
            'status' => 'PENDING',
        ]);

        Message::create([
            'project_id' => $project->id,
            'sender_id' => $insider->id,
            'body' => 'Pesan dari pelamar.',
        ]);

        $this->actingAs($outsider);

        Livewire::test(Inbox::class)
            ->assertViewHas('threads', fn ($threads) => ! $threads->contains('id', $project->id));
    }

    public function test_inbox_requires_authentication(): void
    {
        $this->get('/student/inbox')->assertRedirect('/login');
        $this->get('/umkm/inbox')->assertRedirect('/login');
    }
}
