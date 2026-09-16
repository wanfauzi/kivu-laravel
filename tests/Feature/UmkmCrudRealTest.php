<?php

namespace Tests\Feature;

use App\Livewire\Umkm\CreateProject;
use App\Livewire\Umkm\EditProject;
use App\Models\Category;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UmkmCrudRealTest extends TestCase
{
    use RefreshDatabase;

    public function test_umkm_creates_funds_and_cancels_project()
    {
        $umkm = User::factory()->create(['role' => 'umkm']);
        $cat = Category::create(['name' => 'IT', 'slug' => 'it', 'sort_order' => 1]);
        $skill = Skill::create(['name' => 'PHP', 'slug' => 'php']);

        $this->actingAs($umkm);

        // 1. Create DRAFT
        Livewire::test(CreateProject::class)
            ->set('title', 'Proyek UMKM Baru')
            ->set('description', str_repeat('Desc ', 20))
            ->set('min_budget', 100000)
            ->set('max_budget', 200000)
            ->set('category_id', $cat->id)
            ->set('skillIds', [$skill->id])
            ->call('store');

        $p = Project::first();
        $this->assertEquals('DRAFT', $p->status);

        // 2. Fund & Open
        $p->update(['status' => 'OPEN']); // Simulating payment success

        // 3. Edit
        Livewire::test(EditProject::class, ['project' => $p])
            ->set('title', 'Update Title')
            ->call('update')
            ->assertHasNoErrors();
        $this->assertEquals('Update Title', $p->fresh()->title);

        // 4. Cancel
        Livewire::test(EditProject::class, ['project' => $p])
            ->call('cancelProject');
        $this->assertEquals('CANCELLED', $p->fresh()->status);
    }
}
