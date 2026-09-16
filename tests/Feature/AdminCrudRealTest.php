<?php

namespace Tests\Feature;

use App\Livewire\Admin\Disputes;
use App\Livewire\Admin\Projects;
use App\Livewire\Admin\Users;
use App\Livewire\Admin\Withdrawals;
use App\Models\Dispute;
use App\Models\Project;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminCrudRealTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_complete_management_flow()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student', 'status' => 'pending_ktm', 'ktm_path' => 'ktm/1.jpg']);
        $umkm = User::factory()->create(['role' => 'umkm']);
        Wallet::create(['student_id' => $student->id, 'balance' => 300000]);

        $this->actingAs($admin);

        // 1. Verify KTM
        Livewire::test(Users::class)
            ->call('confirmAction', $student->id, 'verifyKtm')
            ->call('runAction');
        $this->assertEquals('active', $student->fresh()->status);

        // 2. Suspend & Activate
        Livewire::test(Users::class)
            ->call('confirmAction', $student->id, 'suspend')
            ->call('runAction');
        $this->assertEquals('suspended', $student->fresh()->status);

        Livewire::test(Users::class)
            ->call('confirmAction', $student->id, 'activate')
            ->call('runAction');
        $this->assertEquals('active', $student->fresh()->status);

        // 3. Project Moderation (Remove)
        $p = Project::create([
            'owner_id' => $umkm->id,
            'title' => 'Bad Project',
            'description' => str_repeat('Desc ', 20),
            'budget' => 100000,
            'status' => 'OPEN',
        ]);
        Livewire::test(Projects::class)
            ->call('confirmRemove', $p->id)
            ->call('remove');
        $this->assertDatabaseMissing('projects', ['id' => $p->id]);

        // 4. Withdrawal Approval
        $w = Withdrawal::create([
            'student_id' => $student->id,
            'amount' => 50000,
            'status' => 'PENDING',
            'bank_name' => 'BCA',
            'bank_account' => '123',
        ]);
        Livewire::test(Withdrawals::class)
            ->call('confirmApprove', $w->id)
            ->call('runAction');
        $this->assertEquals('APPROVED', $w->fresh()->status);
    }
}
