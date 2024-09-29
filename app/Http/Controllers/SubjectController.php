<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectAttachProjectRequest;
use App\Http\Requests\SubjectDetachProjectRequest;
use App\Http\Requests\SubjectStoreRequest;
use App\Http\Requests\SubjectUpdateRequest;
use App\Http\Resources\SubjectProjectResource;
use App\Http\Resources\SubjectResource;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::all();
        return SubjectResource::collection($subjects);
    }

    public function store(SubjectStoreRequest $request)
    {
        $subject = Subject::create($request->validated());
        return new SubjectResource($subject);
    }

    public function show(Subject $subject)
    {
        return new SubjectResource($subject);
    }

    public function update(SubjectUpdateRequest $request, Subject $subject)
    {
        $subject->update($request->validated());
        return new SubjectResource($subject);
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return response()->noContent();
    }

    public function listProjects(Subject $subject,)
    {
        $projects = $subject->projects;
        return SubjectProjectResource::collection($projects);
    }

    public function attachProjects(SubjectAttachProjectRequest $request, Subject $subject)
    {
        $subject->projects()->syncWithoutDetaching($request->validated('project_ids'));
        return response()->noContent();
    }

    public function detachProjects(SubjectDetachProjectRequest $request, Subject $subject)
    {
        $subject->projects()->detach($request->validated('project_ids'));
        return response()->noContent();
    }
}
