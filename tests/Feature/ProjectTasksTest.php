<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;
    /**
    @test
     */
    public function a_project_can_have_tasks(): void
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this->post($project->path() . '/tasks', ['body' => 'new task']);

        $this->get($project->path())->assertSee(['new task']);
    }

    /**
    @test
     */
    public function it_can_add_task(): void
    {

        $project = Project::factory()->create();

        $task = $project->addTask('add task');

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));

    }

    /**
    @test
     */
    public function a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $task = $project->addTask('Add Task');
        $this->patch($project->path() . '/tasks/' . $task->id,
            [
                'body' => 'Task Updated',
                'completed' => true,
            ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'Task Updated',
            'completed' => true,

        ]);
    }

    /**
    @test
     */
    public function only_the_owner_of_project_may_update_a_task()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $task = $project->addTask('Add new task');

        $this->patch($task->path(), ['body' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }
    /**
    @test
     */
    public function only_the_owner_of_project_can_add_task()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $this->post($project->path() . '/tasks', ['body' => 'Add other Task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Add other Task']);
    }

    /**
    @test
     */
    public function a_task_require_body(): void
    {
        $this->signIn();

        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $attributes = Task::factory()->raw(['body' => '']);
        $this->post($project->path() . '/tasks', $attributes)->assertSessionHasErrors('body');
    }

    /**
    @test
     */
    public function each_task_belong_to_a_project()
    {
        $task = Task::factory()->create();
        $this->assertInstanceOf(Project::class, $task->project);
    }

    /**
    @test
     */
    public function a_task_has_path()
    {
        $task = Task::factory()->create();
        $this->assertEquals('/projects/' . $task->project->id . '/tasks/' . $task->id, $task->path());
    }



    /**
    @test
     */
    public function task_can_be_completed()
    {
        $task = Task::factory()->create();

        $this->assertFalse($task->completed);

        $task->complete();

        $this->assertTrue($task->fresh()->completed);
    }

    /**
    @test
     */
    public function task_can_marked_as_incompleted()
    {
        $task = Task::factory()->create(['completed' => true]);

        $this->assertTrue($task->completed);

        $task->incomplete();

        $this->assertFalse($task->fresh()->completed);
    }

    /**
    @test
     */
    public function incompleting_a_task_activity()
    {
        $project = Project::factory()->create();
        $project->addTask('some new task');

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => " foo bar",
                'completed' => true,
            ]);

        $this->assertCount(3, $project->activity);

        $this->patch($project->tasks[0]->path(), [
            'body' => " foo bar",
            'completed' => false,
        ]);

        $this->assertCount(4, $project->refresh()->activity);

        $this->assertEquals('incompleted_task', $project->refresh()->activity->last()->description);
    }


    /**
    @test
     */
    public function delete_a_task()
    {
        $project = Project::factory()->create();
        $project->addTask('some new task');

        $project->tasks[0]->delete();
        $this->assertCount(3,$project->refresh()->activity);
    }
}
