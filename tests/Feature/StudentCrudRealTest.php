<?php

namespace Tests\Feature;

use App\Livewire\Student\ProjectDetail;
use App\Livewire\Student\SubmitWork;
use App\Livewire\Student\Wallet;
use App\Models\Application;
use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use App\Models\Wallet as WalletModel;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StudentCrudRealTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_full_flow()
    {
        $student = User::factory()->create(['role' => 'student', 'status' => 'active', 'student_verified_at' => now()]);
        $umkm = User::factory()->create(['role' => 'umkm']);
        $cat = Category::create(['name' => 'IT', 'slug' => 'it', 'sort_order' => 1]);
        $p = Project::create([
            'owner_id' => $umkm->id,
            'category_id' => $cat->id,
            'title' => 'Proyek Test',
            'description' => str_repeat('Desc ', 20),
            'budget' => 200000,
            'status' => 'OPEN',
        ]);
        WalletModel::create(['student_id' => $student->id, 'balance' => 500000]);

        $this->actingAs($student);

        $file = \Illuminate\Http\UploadedFile::fake()->create('lamaran.pdf', 100, 'application/pdf');
        Livewire::test(ProjectDetail::class, ['project' => $p])
            ->set('message', 'Minat.')
            ->set('bid_amount', 150000)
            ->set('lamaranFile', $file)
            ->call('apply');
        $this->assertDatabaseHas('applications', ['project_id' => $p->id, 'student_id' => $student->id]);

        $app = Application::first();
        $p->update(['status' => 'OPEN']);

        Livewire::test(SubmitWork::class, ['project' => $p])
            ->set('link', 'https://link.com')
            ->set('note', 'Selesai')
            ->call('confirmSubmit')
            ->call('submit');
        $this->assertEquals('OPEN', $p->fresh()->status);
        $this->assertDatabaseHas('submissions', ['project_id' => $p->id, 'student_id' => $student->id]);

        // Wallet withdraw
        Livewire::test(Wallet::class)
            ->set('amount', 100000)
            ->set('bank_name', 'BCA')
            ->set('bank_account', '123')
            ->call('withdraw');
        $this->assertDatabaseHas('withdrawals', ['student_id' => $student->id, 'status' => 'PENDING']);
    }
}
