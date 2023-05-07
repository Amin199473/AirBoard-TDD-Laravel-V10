<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvitationsRequest;
use App\Models\Project;
use App\Models\User;

class ProjectInvitationsController extends Controller
{
    public function store(Project $project,ProjectInvitationsRequest $request)
    {
        $user = User::where('email', request('email'))->first();
        $project->invite($user);

        return redirect($project->path());
    }
}
