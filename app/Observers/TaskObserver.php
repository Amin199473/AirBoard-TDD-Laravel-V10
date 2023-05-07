<?php

namespace App\Observers;

use App\Models\Task;
use Illuminate\Support\Arr;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        $task->activity()->create([
            'user_id' => $task->project->owner_id,
            'project_id' => $task->project->id,
            'description' => 'Created Task',
            'changes' => [
                'before' => Arr::except(array_diff($task->getOriginal(), $task->getAttributes()), 'updated_at'),
                'after' => Arr::except($task->getChanges(), 'updated_at'),
            ],
        ]);
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        $description = $task->completed ? 'Completed Task Updated' : 'incompleted Task Updated';
        $task->activity()->create([
            'project_id' => $task->project->id,
            'user_id' => $task->project->owner_id,
            'description' => $description,
            'changes' => [
                'before' => Arr::except(array_diff($task->getOriginal(), $task->getAttributes()), 'updated_at'),
                'after' => Arr::except($task->getChanges(), 'updated_at'),
            ],
        ]);
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        $task->activity()->create([
            'project_id' => $task->project->id,
            'user_id' => $task->project->owner_id,
            'description' => 'Deleted Task',
            'changes' => [
                'before' => Arr::except(array_diff($task->getOriginal(), $task->getAttributes()), 'updated_at'),
                'after' => Arr::except($task->getChanges(), 'updated_at'),
            ],
        ]);
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
