<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_process_transaction_and_update_wallet()
    {
        $umkm = User::factory()->create(['role' => 'umkm']);
        $student = User::factory()->create(['role' => 'student']);
        
        $project = Project::create([
            'owner_id' => $umkm->id,
            'title' => 'Project Finance',
            'description' => 'Test finance',
            'budget' => 500000,
            'status' => 'IN_PROGRESS',
        ]);

        $wallet = Wallet::create(['student_id' => $student->id, 'balance' => 0]);

        $tx = Transaction::create([
            'project_id' => $project->id,
            'student_id' => $student->id,
            'type' => 'payment',
            'amount' => 500000,
            'status' => 'RECORDED',
        ]);

        $tx->update(['status' => 'SUCCESS']);
        $wallet->increment('balance', $tx->amount);

        $this->assertEquals(500000, $wallet->fresh()->balance);
        $this->assertDatabaseHas('transactions', ['id' => $tx->id, 'status' => 'SUCCESS']);
    }
}
