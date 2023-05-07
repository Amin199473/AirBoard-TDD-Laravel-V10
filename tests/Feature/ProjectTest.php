<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
    @test
     */
    public function a_user_can_create_a_project(): void
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $this->get('/projects/create')->assertStatus(200);
        $arributes = [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'notes' => $this->faker->sentence(),
        ];
        $this->post('/projects', $arributes);
        $this->assertDatabaseHas('projects', $arributes);

        $project = Project::where($arributes)->first();

        $this->get($project->path())
            ->assertSee($arributes['title'])
            ->assertSee($arributes['description'])
            ->assertSee($arributes['notes']);
    }

    /**
    @test
     */
    public function a_user_can_delete_a_project(): void
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $this->delete($project->path())->assertRedirect('/projects');
        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /**
    @test
     */
    public function unauthorize_user_cant_delete_projects(): void
    {
        $project = Project::factory()->create();
        $this->delete($project->path())->assertRedirect('/login');
        $this->signIn();
        $this->delete($project->path())->assertStatus(403);
        $user = User::factory()->create();
        $project->invite($user);
        $this->actingAs($user)->delete($project->path())->assertStatus(403);
    }

    /**
    @test
     */
    public function a_guess_cant_delete_a_project(): void
    {
        $project = Project::factory()->create();
        $this->delete($project->path())->assertRedirect('/login');
        $this->signIn();
        $this->delete($project->path())->assertStatus(403);
    }

    /**
    @test
     */
    public function a_user_can_update_a_project()
    {
        $this->signIn();
        // $this->withoutExceptionHandling();

        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this->patch($project->path(), [
            'notes' => 'note updated',
            'title' => 'title updated',
            'description' => 'description updated',
        ])->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', [
            'notes' => 'note updated',
            'title' => 'title updated',
            'description' => 'description updated',
        ]);
    }

    /**
    @test
     */
    public function a_user_can_see_their_all_projects_have_been_invited_to()
    {

        $newUser = User::factory()->create();
        $project = Project::factory()->create();
        $project->invite($newUser);
        $this->get('/projects')
            ->assertSee($project->title);

    }

    /**
    @test
     */
    public function a_user_has_accessible_projects()
    {

        $john = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $john->id]);
        $this->assertCount(1, $john->accessibleProjects());

        $sally = User::factory()->create();
        Project::factory()->create(['owner_id' => $sally->id])->invite($sally);
        $this->assertCount(1, $sally->accessibleProjects());

    }

    /**
    @test
     */
    public function a_user_can_update_a_project_general_Notes()
    {
        $this->signIn();
        // $this->withoutExceptionHandling();

        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $this->patch($project->path(), [
            'notes' => 'note updated',
        ])->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', [
            'notes' => 'note updated',
        ]);
    }

    /**
    @test
     */
    public function a_user_can_view_their_project(): void
    {

        $this->signIn();
        $this->withoutExceptionHandling();

        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /**
    @test
     */
    public function it_has_path(): void
    {
        $project = Project::factory()->create();
        $this->assertEquals('/projects/' . $project->id, $project->path());
    }

    /**
    @test
     */
    public function a_project_requires_title(): void
    {
        $this->signIn();
        $arributes = Project::factory()->raw(['title' => '']);

        $this->post('/projects', $arributes)->assertSessionHasErrors('title');
    }

    /**
    @test
     */
    public function a_project_requires_description(): void
    {
        $this->signIn();
        $arributes = Project::factory()->raw(['description' => '']);
        $this->post('/projects', $arributes)->assertSessionHasErrors('description');
    }

    /**
    @test
     */
    public function guests_can_not_manage_project(): void
    {

        $project = Project::factory()->create();
        $this->post('/projects', $project->toArray())->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path() . '/edit')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->get('/projects')->assertRedirect('login');
    }

    /**
    @test
     */
    public function guests_can_not_view_projects(): void
    {
        $this->get('/projects')->assertRedirect('login');
    }

    /**
    @test
     */
    public function an_authenticated_user_cannot_view_the_projects_of_others(): void
    {
        $this->signIn();

        $this->withoutExceptionHandling();

        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);
    }

    /**
    @test
     */
    public function an_authenticated_user_cannot_update_the_projects_of_others(): void
    {
        $this->signIn();
        $project = Project::factory()->create();

        $this->patch($project->path(), [])->assertStatus(403);
    }

    /**
    @test
     */
    public function it_projcet_belongs_to_a_owner(): void
    {
        $project = Project::factory()->create();
        $this->assertInstanceOf("App\Models\User", $project->owner);
    }
}
