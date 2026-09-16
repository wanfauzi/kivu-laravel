<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\Application;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiRoleRealDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_multi_role_real_data_workflows()
    {
        $category = Category::create([
            'name' => 'Technology',
            'slug' => 'technology',
            'sort_order' => 1,
        ]);

        // 1. Create 1 Admin
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'name' => 'Admin Boss',
        ]);

        // 2. Create 5 UMKM Users & 5 Projects each
        $umkmUsers = [];
        for ($u = 1; $u <= 5; $u++) {
            $umkm = User::factory()->create([
                'role' => 'umkm',
                'status' => 'active',
                'business_name' => "UMKM Bisnis {$u}",
                'name' => "UMKM User {$u}",
            ]);
            $umkmUsers[] = $umkm;

            for ($p = 1; $p <= 5; $p++) {
                Project::create([
                    'owner_id' => $umkm->id,
                    'category_id' => $category->id,
                    'title' => "Proyek UMKM {$u} - {$p}",
                    'description' => "Deskripsi proyek {$p} dari UMKM {$u}",
                    'budget' => 500000 + ($p * 100000),
                    'status' => 'OPEN',
                ]);
            }
        }
        $this->assertDatabaseCount('projects', 25);

        // 3. Create 5 Student Users (some pending KTM, some active)
        $studentUsers = [];
        for ($s = 1; $s <= 5; $s++) {
            $status = $s === 5 ? 'pending_ktm' : 'active';
            $student = User::factory()->create([
                'role' => 'student',
                'status' => $status,
                'name' => "Mahasiswa {$s}",
                'student_verified_at' => $status === 'active' ? now() : null,
            ]);
            Wallet::create([
                'student_id' => $student->id,
                'balance' => $s * 100000,
            ]);
            $studentUsers[] = $student;
        }
        $this->assertDatabaseCount('wallets', 5);

        // 4. Test Student Actions: Apply to projects
        $activeStudent = $studentUsers[0];
        $targetProject = Project::first();
        
        $application = Application::create([
            'project_id' => $targetProject->id,
            'student_id' => $activeStudent->id,
            'status' => 'PENDING',
        ]);
        $this->assertDatabaseHas('applications', ['id' => $application->id, 'status' => 'PENDING']);

        // 5. Test UMKM Actions: Accept Application
        $umkmOwner = User::find($targetProject->owner_id);
        $this->actingAs($umkmOwner);
        
        $application->update(['status' => 'ACCEPTED']);
        $targetProject->update(['status' => 'IN_PROGRESS']);
        
        $this->assertEquals('ACCEPTED', $application->fresh()->status);
        $this->assertEquals('IN_PROGRESS', $targetProject->fresh()->status);

        // 6. Test Admin Actions: Verify Pending KTM Student
        $pendingStudent = $studentUsers[4];
        $this->assertEquals('pending_ktm', $pendingStudent->status);

        $this->actingAs($admin);
        $pendingStudent->update([
            'status' => 'active',
            'student_verified_at' => now(),
        ]);

        $this->assertEquals('active', $pendingStudent->fresh()->status);
        $this->assertNotNull($pendingStudent->fresh()->student_verified_at);
    }
}
