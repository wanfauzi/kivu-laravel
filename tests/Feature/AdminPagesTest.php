<?php

namespace Tests\Feature;

use App\Livewire\Admin\Disputes;
use App\Livewire\Admin\Projects;
use App\Livewire\Admin\Users;
use App\Livewire\Admin\Withdrawals;
use App\Models\Application;
use App\Models\Dispute;
use App\Models\Project;
use App\Models\ProjectFund;
use App\Models\Submission;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPagesTest extends TestCase
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

    public function test_admin_verifies_ktm_of_pending_student(): void
    {
        $admin = $this->makeUser('admin');
        $student = $this->makeUser('student', ['status' => 'pending_ktm', 'ktm_path' => 'ktm/1/ktm.jpg']);

        $this->actingAs($admin);

        Livewire::test(Users::class)
            ->call('confirmAction', $student->id, 'verifyKtm')
            ->call('runAction');

        $student->refresh();

        $this->assertSame('active', $student->status);
        $this->assertNotNull($student->student_verified_at);
    }

    public function test_admin_suspends_then_activates_user(): void
    {
        $admin = $this->makeUser('admin');
        $student = $this->makeUser('student');

        $this->actingAs($admin);

        Livewire::test(Users::class)
            ->call('confirmAction', $student->id, 'suspend')
            ->call('runAction');
        $this->assertSame('suspended', $student->fresh()->status);

        Livewire::test(Users::class)
            ->call('confirmAction', $student->id, 'activate')
            ->call('runAction');
        $this->assertSame('active', $student->fresh()->status);
    }

    public function test_admin_can_takedown_open_project(): void
    {
        $admin = $this->makeUser('admin');
        $owner = $this->makeUser('umkm');

        $project = Project::create([
            'owner_id' => $owner->id,
            'title' => 'Proyek Nakal',
            'description' => 'Butuh takedown.',
            'budget' => 200000,
            'status' => 'OPEN',
        ]);

        $this->actingAs($admin);

        Livewire::test(Projects::class)
            ->call('confirmRemove', $project->id)
            ->call('remove');

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_admin_approves_withdrawal(): void
    {
        $admin = $this->makeUser('admin');
        $student = $this->makeUser('student');

        Wallet::create(['student_id' => $student->id, 'balance' => 0]);

        $withdrawal = Withdrawal::create([
            'student_id' => $student->id,
            'amount' => 150000,
            'status' => 'PENDING',
            'bank_name' => 'BCA',
            'bank_account' => '1234567890',
        ]);

        $this->actingAs($admin);

        Livewire::test(Withdrawals::class)
            ->call('confirmApprove', $withdrawal->id)
            ->call('runAction');

        $transaction = Transaction::firstWhere('withdrawal_id', $withdrawal->id);

        $this->assertSame('APPROVED', $withdrawal->fresh()->status);
        $this->assertNotNull($transaction);
        $this->assertSame('SUCCESS', $transaction->status);
    }

    public function test_admin_releases_dispute_and_pays_student(): void
    {
        $admin = $this->makeUser('admin');
        $owner = $this->makeUser('umkm');
        $student = $this->makeUser('student');

        Wallet::create(['student_id' => $student->id, 'balance' => 0]);

        $project = Project::create([
            'owner_id' => $owner->id,
            'title' => 'Video Promosi',
            'description' => 'Butuh video.',
            'budget' => 800000,
            'status' => 'SUBMITTED',
        ]);

        Application::create(['project_id' => $project->id, 'student_id' => $student->id, 'status' => 'ACCEPTED']);

        $submission = Submission::create([
            'project_id' => $project->id,
            'student_id' => $student->id,
            'link' => 'https://drive.google.com/hasil',
            'status' => 'SUBMITTED',
        ]);

        $dispute = Dispute::create([
            'project_id' => $project->id,
            'reporter_id' => $owner->id,
            'against_id' => $student->id,
            'reason' => 'not_paid',
            'description' => 'Pembayaran belum cair.',
            'status' => 'OPEN',
        ]);

        ProjectFund::create([
            'project_id' => $project->id,
            'umkm_id' => $owner->id,
            'amount' => 800000,
            'reference' => 'QR-TEST123',
            'status' => 'LOCKED',
            'funded_at' => now(),
        ]);

        $this->actingAs($admin);

        Livewire::test(Disputes::class)
            ->call('confirmAction', $dispute->id, 'release')
            ->call('runAction');

        $this->assertSame('RESOLVED', $dispute->fresh()->status);
        $this->assertSame('APPROVED', $submission->fresh()->status);
        $this->assertSame('COMPLETED', $project->fresh()->status);
        $this->assertSame(800000, Wallet::where('student_id', $student->id)->value('balance'));
    }
}
