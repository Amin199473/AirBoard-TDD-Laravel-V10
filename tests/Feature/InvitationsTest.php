<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;
    /**
    @test
     */
    public function invited_userse_may_update_proejct_details(): void
    {
        $project = Project::factory()->create();
        $newUser = User::factory()->create();
        $project->invite($newUser);
        $this->signIn($newUser);
        $task = ['body' => 'foo'];
        $this->post($project->path() . '/tasks', $task);

        $this->assertDatabaseHas('tasks', $task);
    }

    /**
    @test
     */
    public function it_can_invite_user(): void
    {
        $project = Project::factory()->create();
        $newUser = User::factory()->create();
        $project->invite($newUser);
        $this->assertTrue($project->members->contains($newUser));
    }

    /**
    @test
     */
    public function a_project_owner_can_invite_a_user(): void
    {
        $this->withoutExceptionHandling();
        $project = Project::factory()->create();
        $userToInvite = User::factory()->create();
        $this->actingAs($project->owner)->post($project->path() . '/invitations', [
            'email' => $userToInvite->email,
        ])->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($userToInvite));
    }

    /**
    @test
     */
    public function the_email_address_must_be_associated_valid_birdboard_account(): void
    {
        $project = Project::factory()->create();

        $this->actingAs($project->owner)->post($project->path() . '/invitations', [
            'email' => 'not_a_user_@gmail.com',
        ])->assertSessionHasErrors(
            [
                'email' => "the emil you invited must be birdboard Accounts",
            ]
        );

    }

    /**
    @test
     */
    public function non_owners_may_not_invite_user(): void
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user)->post($project->path() . '/invitations')
            ->assertStatus(403);

        $project->invite($user);
        $this->actingAs($user)->post($project->path() . '/invitations')
            ->assertStatus(403);
    }
}
