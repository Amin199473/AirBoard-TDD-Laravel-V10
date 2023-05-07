<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Activity;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProjectRequest;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Auth::user()->accessibleProjects();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        $user_id = auth()->user()->id;
        $attributes = $request->all() + ['owner_id' => $user_id];
        $project = Project::create($attributes);

        return redirect($project->path());
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $activites = Activity::latest()->get();
        $this->authorize('update', $project);
        return view('projects.show', compact('project','activites'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, Project $project)
    {

        $this->authorize('update', $project);
        $project->update($request->validated());
        return redirect($project->path());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);
        $project->delete();
        return redirect('/projects');
    }
}
