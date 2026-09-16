<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RealDataCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_perform_crud_on_projects_with_real_data()
    {
        $owner = User::factory()->create(['role' => 'umkm']);
        $category = Category::create(['name' => 'Web Development', 'slug' => 'web-development', 'sort_order' => 1]);

        $rows = [];
        for ($i = 1; $i <= 50; $i++) {
            $rows[] = [
                'owner_id' => $owner->id,
                'category_id' => $category->id,
                'title' => "Project Real Data {$i}",
                'description' => "Description for project {$i}",
                'budget' => 1000000 + ($i * 10000),
                'status' => 'OPEN',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Project::insert($rows);

        $this->assertDatabaseCount('projects', 50);
        $this->assertDatabaseHas('projects', ['title' => 'Project Real Data 25']);

        $p25 = Project::where('title', 'Project Real Data 25')->first();
        $this->assertNotNull($p25);
        $this->assertEquals(1250000, $p25->budget);

        $p25->update(['title' => 'Updated Project Title 25', 'budget' => 9999999]);
        $this->assertDatabaseHas('projects', ['id' => $p25->id, 'title' => 'Updated Project Title 25', 'budget' => 9999999]);

        $p50 = Project::where('title', 'Project Real Data 50')->first();
        $p50->delete();
        $this->assertDatabaseCount('projects', 49);
        $this->assertDatabaseMissing('projects', ['title' => 'Project Real Data 50']);
    }
}
