<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubjectController;

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Project routes

    Route::apiResource('/projects', ProjectController::class);
    Route::get('/projects/{project}/subjects', [ProjectController::class, 'listSubjects']);
    Route::post('/projects/{project}/subjects', [ProjectController::class, 'attachSubjects']);
    Route::delete('/projects/{project}/subjects', [ProjectController::class, 'detachSubjects']);

    // Subject routes

    Route::apiResource('/subjects', SubjectController::class);
    Route::get('/subjects/{subject}/projects', [SubjectController::class, 'listProjects']);
    Route::post('/subjects/{subject}/projects', [SubjectController::class, 'attachProjects']);
    Route::delete('/subjects/{subject}/projects', [SubjectController::class, 'detachProjects']);
});
