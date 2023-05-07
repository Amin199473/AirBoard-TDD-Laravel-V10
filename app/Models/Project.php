<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function addTask(string $body)
    {
        return $this->tasks()->create(['body' => $body]);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function activity(): MorphMany
    {
        return $this->MorphMany(Activity::class, 'subject')->latest();
    }

    public function invite(User $user)
    {
        return $this->members()->attach($user);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'project_members')->withTimestamps();
    }

}
