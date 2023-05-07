<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityFeedsTest extends TestCase
{
    use RefreshDatabase;
    /**
    @test
     */
    public function creating_project_records_activity(): void
    {
        $project = Project::factory()->create();

        $this->assertCount(1, $project->activity);

        $this->assertEquals('Created Project', $project->activity[0]->description);
    }

    /**
    @test
     */
    public function updating_project_records_activity(): void
    {

        $project = Project::factory()->create();
        $orginalTitle = $project->title;
        $project->update(['title' => 'changed']);
        $this->assertCount(2, $project->activity);
        $this->assertEquals('Updated Project', $project->activity->last()->description);

        $expected = [
            'before' => ['title' => $orginalTitle],
            'after' => ['title' => 'changed'],
        ];

        $this->assertEquals($expected, $project->activity->last()->changes);
    }

    /**
    @test
     */
    public function creating_a_new_task_records_project_activity(): void
    {
        $project = Project::factory()->create();

        $project->addTask('some task');
        $this->assertCount(1, $project->tasks[0]->activity);
        $this->assertEquals('Created Task', $project->tasks[0]->activity->last()->description);
        $this->assertInstanceOf(Task::class, $project->tasks[0]->activity->last()->subject);
    }

    /**
    @test
     */
    public function completing_a_new_task_records_project_activity(): void
    {
        $project = Project::factory()->create();

        $project->addTask('some task');

        $this->actingAs($project->owner)->patch($project->tasks[0]->path(), [
            'body' => 'foo bar',
            'completed' => true,
        ]);
        $this->assertCount('3', $project->tasks[0]->activity);
        $this->assertEquals('Completed Task Updated', $project->tasks[0]->activity->last()->description);
        $this->assertInstanceOf(Task::class, $project->tasks[0]->activity->last()->subject);

    }

    /**
    @test
     */
    public function Activity_has_a_user(): void
    {
        $project = Project::factory()->create();
        $this->assertInstanceOf(User::class, $project->activity->first()->user);

    }
}
