<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            Project::create([
                'name' => 'Project 1',
                'description' => 'Lorem ipsum',
            ]),
            Project::create([
                'name' => 'Project 2',
                'description' => 'Lorem ipsum',
            ]),
            Project::create([
                'name' => 'Project 3',
                'description' => 'Lorem ipsum',
            ])
        ];

        $subjects = [
            Subject::create([
                'email' => 'test1@testing.com',
                'first_name' => 'Robert',
                'last_name' => 'Downey Jr.',
            ]),
            Subject::create([
                'email' => 'test2@testing.com',
                'first_name' => 'Chris',
                'last_name' => 'Hemsworth',
            ]),
            Subject::create([
                'email' => 'test3@testing.com',
                'first_name' => 'Scarlett',
                'last_name' => 'Johansson',
            ]),
        ];

        $projects[0]->subjects()->attach($subjects[0]);
        $projects[0]->subjects()->attach($subjects[1]);
        $projects[1]->subjects()->attach($subjects[1]);
        $projects[1]->subjects()->attach($subjects[2]);
        $projects[2]->subjects()->attach($subjects[0]);
        $projects[2]->subjects()->attach($subjects[2]);
    }
}
