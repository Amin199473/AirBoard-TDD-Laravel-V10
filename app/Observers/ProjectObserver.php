<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Project;
use Illuminate\Support\Arr;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        $project->activity()->create([
            'user_id' => $project->owner_id,
            'project_id' => $project->id,
            'description' => 'Created Project',
            'changes' => [
                'before' => Arr::except(array_diff($project->getOriginal(), $project->getAttributes()), 'updated_at'),
                'after' => Arr::except($project->getChanges(), 'updated_at'),
            ],
        ]);
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        $project->activity()->create([
            'user_id' => $project->owner_id,
            'project_id' => $project->id,
            'description' => 'Updated Project',
            'changes' => [
                'before' => Arr::except(array_diff($project->getOriginal(), $project->getAttributes()), 'updated_at'),
                'after' => Arr::except($project->getChanges(), 'updated_at'),
            ],
        ]);
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        $activity = Activity::where('project_id', $project->id)->delete();
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
