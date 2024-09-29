<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    // List projects

    public function test_list_returns_array_of_projects(): void
    {
        $this->getJson('/api/v1/projects')
            ->assertSuccessful()
            ->assertExactJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'created_at',
                        'updated_at',
                        'subjects_count',
                    ],
                ],
            ]);
    }

    // Get project

    public function test_get_with_invalid_id_returns_not_found(): void
    {
        $this->getJson('/api/v1/projects/4')
            ->assertNotFound();
    }

    public function test_get_with_valid_id_returns_project(): void
    {
        $this->getJson('/api/v1/projects/1')
            ->assertSuccessful()
            ->assertExactJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'created_at',
                    'updated_at',
                    'subjects_count',
                ],
            ]);
    }

    // Create project

    public function test_create_with_invalid_data_returns_error(): void
    {
        $data = [
            'description' => 'Description 4',
        ];

        $this->postJson('/api/v1/projects', $data)
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('name');

        $this->assertDatabaseMissing('projects', $data);
    }

    public function test_create_with_valid_data_returns_project(): void
    {
        $data = [
            'name' => 'Project 4',
            'description' => null,
        ];

        $response = $this->postJson('/api/v1/projects', $data)
            ->assertCreated()
            ->assertExactJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'created_at',
                    'updated_at',
                    'subjects_count',
                ],
            ]);

        $this->assertDatabaseHas('projects', [
            'id' => $response['data']['id'],
            ...$data,
        ]);
    }

    // Update project

    public function test_update_with_valid_id_returns_project(): void
    {
        $data = [
            'name' => 'Project 4',
            'description' => 'Description 4',
        ];

        $this->putJson('/api/v1/projects/1', $data)
            ->assertSuccessful()
            ->assertExactJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'created_at',
                    'updated_at',
                    'subjects_count',
                ],
            ]);

        $this->assertDatabaseHas('projects', [
            'id' => 1,
            ...$data
        ]);
    }

    // Delete project

    public function test_delete_with_valid_id_returns_no_content(): void
    {
        $this->deleteJson('/api/v1/projects/1')
            ->assertNoContent();

        $this->assertDatabaseMissing('projects', [
            'id' => 1,
        ]);
    }

    // List project subjects

    public function test_list_subjects_returns_array_of_subjects(): void
    {
        $this->getJson('/api/v1/projects/1/subjects')
            ->assertSuccessful()->assertExactJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'email',
                        'first_name',
                        'last_name',
                    ],
                ]
            ]);
    }

    // Attach subjects to project

    public function test_attach_subjects_with_invalid_ids_returns_error(): void
    {
        $this->postJson('/api/v1/projects/1/subjects', [
            'subject_ids' => [1, 2, 4],
        ])
            ->assertUnprocessable();
        $this->assertCount(2, Project::find(1)->subjects);
    }

    public function test_attach_subjects_with_valid_ids_returns_no_content(): void
    {
        $this->postJson('/api/v1/projects/1/subjects', [
            'subject_ids' => [1, 2, 3],
        ])
            ->assertNoContent();
        $this->assertCount(3, Project::find(1)->subjects);
    }

    // Detach subjects from project

    public function test_detach_subjects_with_invalid_ids_returns_error(): void
    {
        $this->deleteJson('/api/v1/projects/1/subjects', [
            'subject_ids' => [4],
        ])
            ->assertUnprocessable();
        $this->assertCount(2, Project::find(1)->subjects);
    }

    public function test_detach_subjects_with_valid_ids_returns_no_content(): void
    {
        $this->deleteJson('/api/v1/projects/1/subjects', [
            'subject_ids' => [1, 2],
        ])
            ->assertNoContent();
        $this->assertCount(0, Project::find(1)->subjects);
    }
}
