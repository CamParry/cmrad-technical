<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Subject;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    // List subjects

    public function test_list_returns_array_of_subjects(): void
    {
        $this->getJson('/api/v1/subjects')
            ->assertSuccessful()
            ->assertExactJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'email',
                        'first_name',
                        'last_name',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    // Get subject

    public function test_get_with_invalid_id_returns_not_found(): void
    {
        $this->getJson('/api/v1/subjects/4')
            ->assertNotFound();
    }

    public function test_get_with_valid_id_returns_project(): void
    {
        $this->getJson('/api/v1/subjects/1')
            ->assertSuccessful()
            ->assertExactJsonStructure([
                'data' => [
                    'id',
                    'email',
                    'first_name',
                    'last_name',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    // Create subject

    public function test_create_with_invalid_email_returns_error(): void
    {
        $data = [
            'email' => 'test4.testing.com',
            'first_name' => 'Mark',
            'last_name' => 'Ruffalo',
        ];

        $this->postJson('/api/v1/subjects', $data)
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('email');

        $this->assertDatabaseMissing('subjects', $data);
    }

    public function test_create_with_existing_email_returns_error(): void
    {
        $data = [
            'email' => 'test3@testing.com',
            'first_name' => 'Mark',
            'last_name' => 'Ruffalo',
        ];

        $this->postJson('/api/v1/subjects', $data)
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('email');

        $this->assertDatabaseMissing('subjects', $data);
    }

    public function test_create_with_valid_data_returns_subject(): void
    {
        $data = [
            'email' => 'test4@testing.com',
            'first_name' => 'Mark',
            'last_name' => 'Ruffalo',
        ];

        $response = $this->postJson('/api/v1/subjects', $data)
            ->assertCreated()
            ->assertExactJsonStructure([
                'data' => [
                    'id',
                    'email',
                    'first_name',
                    'last_name',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('subjects', [
            'id' => $response['data']['id'],
            ...$data,
        ]);
    }

    // Update subject

    public function test_update_with_valid_id_returns_subject(): void
    {
        $data = [
            'email' => 'test4@testing.com',
            'first_name' => 'Mark',
            'last_name' => 'Ruffalo',
        ];

        $this->putJson('/api/v1/subjects/1', $data)
            ->assertSuccessful()
            ->assertExactJsonStructure([
                'data' => [
                    'id',
                    'email',
                    'first_name',
                    'last_name',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('subjects', [
            'id' => 1,
            ...$data
        ]);
    }

    // Delete subject

    public function test_delete_with_valid_id_returns_no_content(): void
    {
        $this->deleteJson('/api/v1/subjects/1')
            ->assertNoContent();
    }

    // List subject projects

    public function test_list_projects_returns_array_of_projects(): void
    {
        $this->getJson('/api/v1/subjects/1/projects')
            ->assertSuccessful()->assertExactJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                    ],
                ]
            ]);
    }

    // Attach projects to subject

    public function test_attach_projects_with_invalid_ids_returns_error(): void
    {
        $this->postJson('/api/v1/subjects/1/projects', [
            'project_ids' => [1, 2, 4],
        ])
            ->assertUnprocessable();
        $this->assertCount(2, Subject::find(1)->projects);
    }

    public function test_attach_projects_with_valid_ids_returns_no_content(): void
    {
        $this->postJson('/api/v1/subjects/1/projects', [
            'project_ids' => [1, 2, 3],
        ])
            ->assertNoContent();
        $this->assertCount(3, Subject::find(1)->projects);
    }

    // Detach projects from subject

    public function test_detach_projects_with_invalid_ids_returns_error(): void
    {
        $this->deleteJson('/api/v1/subjects/1/projects', [
            'project_ids' => [4],
        ])
            ->assertUnprocessable();
        $this->assertCount(2, Subject::find(1)->projects);
    }

    public function test_detach_projects_with_valid_ids_returns_no_content(): void
    {
        $this->deleteJson('/api/v1/subjects/1/projects', [
            'project_ids' => [1, 3],
        ])
            ->assertNoContent();
        $this->assertCount(0, Subject::find(1)->projects);
    }
}
