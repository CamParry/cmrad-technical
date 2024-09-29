<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectAttachSubjectRequest;
use App\Http\Requests\ProjectDetachSubjectRequest;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectSubjectResource;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount('subjects')->get();
        return ProjectResource::collection($projects);
    }

    public function store(ProjectStoreRequest $request)
    {
        $project = Project::create($request->validated());
        $project->loadCount('subjects');
        return new ProjectResource($project);
    }

    public function show(Project $project)
    {
        $project->loadCount('subjects');
        return new ProjectResource($project);
    }

    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $project->update($request->validated());
        $project->loadCount('subjects');
        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->noContent();
    }

    public function listSubjects(Project $project)
    {
        $subjects = $project->subjects;
        return ProjectSubjectResource::collection($subjects);
    }

    public function attachSubjects(ProjectAttachSubjectRequest $request, Project $project)
    {
        $project->subjects()->syncWithoutDetaching($request->validated('subject_ids'));
        return response()->noContent();
    }

    public function detachSubjects(ProjectDetachSubjectRequest $request, Project $project)
    {
        $project->subjects()->detach($request->validated('subject_ids'));
        return response()->noContent();
    }
}
