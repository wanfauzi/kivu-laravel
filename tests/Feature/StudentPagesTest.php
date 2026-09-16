<?php

namespace Tests\Feature;

use App\Livewire\Student\Opportunities;
use App\Livewire\Student\SubmitWork;
use App\Livewire\Student\Wallet;
use App\Models\Application;
use App\Models\Category;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Submission;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet as WalletModel;
use App\Models\Withdrawal;
use Database\Seeders\CategorySeeder;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class StudentPagesTest extends TestCase
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

    private function makeProject(User $owner, array $attributes = []): Project
    {
        return Project::create(array_merge([
            'owner_id' => $owner->id,
            'title' => 'Proyek '.uniqid(),
            'description' => 'Deskripsi proyek.',
            'budget' => 500000,
            'status' => 'OPEN',
        ], $attributes));
    }

    public function test_opportunities_filters_by_category_skill_and_deadline(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(SkillSeeder::class);

        $owner = $this->makeUser('umkm');

        $web = $this->makeProject($owner, [
            'title' => 'Landing Page',
            'category_id' => Category::where('slug', 'web-it')->value('id'),
            'due_date' => now()->addDays(3),
        ]);
        $web->skills()->sync([Skill::where('slug', 'web-development')->value('id')]);

        $design = $this->makeProject($owner, [
            'title' => 'Desain Logo',
            'category_id' => Category::where('slug', 'desain-grafis')->value('id'),
            'due_date' => now()->addDays(40),
        ]);

        $student = $this->makeUser('student');
        $this->actingAs($student);

        Livewire::test(Opportunities::class)
            ->call('selectCategory', 'web-it')
            ->assertViewHas('projects', fn ($p) => $p->pluck('title')->contains('Landing Page') && ! $p->pluck('title')->contains('Desain Logo'));

        Livewire::test(Opportunities::class)
            ->call('selectSkill', 'web-development')
            ->assertViewHas('projects', fn ($p) => $p->pluck('title')->contains('Landing Page'));

        Livewire::test(Opportunities::class)
            ->set('deadlineFilter', 'soon')
            ->assertViewHas('projects', fn ($p) => $p->pluck('title')->contains('Landing Page') && ! $p->pluck('title')->contains('Desain Logo'));

        Livewire::test(Opportunities::class)
            ->call('resetFilter')
            ->assertSet('category', '')
            ->assertSet('skill', '')
            ->assertSet('deadlineFilter', '');
    }

    public function test_pending_student_can_submit_work_with_link(): void
    {
        $owner = $this->makeUser('umkm');
        $student = $this->makeUser('student');

        $project = $this->makeProject($owner, ['status' => 'OPEN']);

        Application::create([
            'project_id' => $project->id,
            'student_id' => $student->id,
            'status' => 'PENDING',
        ]);

        $this->actingAs($student);

        Livewire::test(SubmitWork::class, ['project' => $project])
            ->set('link', 'https://drive.google.com/hasil')
            ->set('note', 'Sudah selesai.')
            ->call('confirmSubmit')
            ->assertSet('confirming', true)
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('submissions', [
            'project_id' => $project->id,
            'student_id' => $student->id,
            'status' => 'SUBMITTED',
            'link' => 'https://drive.google.com/hasil',
        ]);

        $this->assertSame('OPEN', $project->fresh()->status);
        $this->assertSame(1, $owner->refresh()->notifications()->count());
    }

    public function test_pending_student_can_submit_work_with_file(): void
    {
        Storage::fake('local');

        $owner = $this->makeUser('umkm');
        $student = $this->makeUser('student');

        $project = $this->makeProject($owner, ['status' => 'OPEN']);

        Application::create([
            'project_id' => $project->id,
            'student_id' => $student->id,
            'status' => 'PENDING',
        ]);

        $this->actingAs($student);

        $file = UploadedFile::fake()->create('hasil.pdf', 100, 'application/pdf');

        Livewire::test(SubmitWork::class, ['project' => $project])
            ->set('file', $file)
            ->call('confirmSubmit')
            ->call('submit')
            ->assertHasNoErrors();

        $submission = Submission::firstWhere('project_id', $project->id);

        $this->assertNotNull($submission);
        $this->assertNotNull($submission->file_path);
        Storage::disk('local')->assertExists($submission->file_path);
    }

    public function test_user_without_application_cannot_submit_work(): void
    {
        $owner = $this->makeUser('umkm');
        $student = $this->makeUser('student');

        $project = $this->makeProject($owner, ['status' => 'OPEN']);

        $this->actingAs($student);

        Livewire::test(SubmitWork::class, ['project' => $project])
            ->set('link', 'https://drive.google.com/hasil')
            ->call('confirmSubmit');

        $this->assertDatabaseMissing('submissions', [
            'project_id' => $project->id,
            'student_id' => $student->id,
        ]);
    }

    public function test_submit_work_requires_file_or_link(): void
    {
        $owner = $this->makeUser('umkm');
        $student = $this->makeUser('student');

        $project = $this->makeProject($owner, ['status' => 'OPEN']);

        Application::create([
            'project_id' => $project->id,
            'student_id' => $student->id,
            'status' => 'PENDING',
        ]);

        $this->actingAs($student);

        Livewire::test(SubmitWork::class, ['project' => $project])
            ->call('confirmSubmit')
            ->assertHasErrors('link');
    }

    public function test_transaction_direction_distinguishes_credits_and_debits(): void
    {
        $student = $this->makeUser('student');

        $payment = Transaction::create(['student_id' => $student->id, 'amount' => 100, 'type' => 'payment', 'status' => 'SUCCESS']);
        $refund = Transaction::create(['student_id' => $student->id, 'amount' => 50, 'type' => 'refund', 'status' => 'SUCCESS']);
        $withdrawn = Transaction::create(['student_id' => $student->id, 'amount' => 25, 'type' => 'withdrawal', 'status' => 'SUCCESS']);
        $returned = Transaction::create(['student_id' => $student->id, 'amount' => 25, 'type' => 'withdrawal', 'status' => 'REJECTED']);

        $this->assertSame('in', $payment->direction());
        $this->assertSame('out', $refund->direction());
        $this->assertSame('out', $withdrawn->direction());
        $this->assertSame('in', $returned->direction());
        $this->assertSame('Penarikan Dibatalkan', $returned->displayLabel());
    }

    public function test_cancelling_withdrawal_returns_balance(): void
    {
        $student = $this->makeUser('student');

        $wallet = WalletModel::create(['student_id' => $student->id, 'balance' => 0]);

        $withdrawal = Withdrawal::create([
            'student_id' => $student->id,
            'amount' => 100000,
            'status' => 'PENDING',
            'bank_name' => 'BCA',
            'bank_account' => '1234567890',
        ]);

        $this->actingAs($student);

        Livewire::test(Wallet::class)
            ->call('confirmCancel', $withdrawal->id)
            ->call('cancelWithdrawal');

        $this->assertSame('CANCELLED', $withdrawal->fresh()->status);
        $this->assertSame(100000, $wallet->fresh()->balance);
    }
}
