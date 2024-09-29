# CMRAD Technical

A simple API to manage clinical trial Projects and Subjects

## How to run the app

```bash

git clone https://github.com/CamParry/cmrad-technical.git

cd cmrad-technical

composer install

php artisan key:generate

php artisan migrate

php artisan serve

or

php artisan test

```

## Notes and considerations

-   While the app is setup to support authentication, I have left the endpoints publicly accessible for simplicity
-   For this small app I decided to use SQLite for the database
-   I've created feature tests for all API endpoints using an in memory squlite database and seeded data
-   I created two endpoints to list all subjects for a project and to list all projects for a subject
-   I created assignment and unassignment endpoints for both for projects and subjects, this is so that a project can easily have multiple subjects assigned in bulk, and also so a subject can easily be assigned to multiple projects in bulk

## API routes

GET /projects
POST /projects
GET /projects/{project}
PUT /projects/{project}
DELETE /projects/{project}

GET /projects/{project}/subjects
POST /projects/{project}/subjects
DELETE /projects/{project}/subjects

GET /subjects
POST /subjects
GET /subjects/{subject}
PUT /subjects/{subject}
DELETE /subjects/{subject}

GET /subjects/{subject}/projects
POST /subjects/{subject}/projects
DELETE /subjects/{subject}/projects

## Database schemas

### projects

-   id (primary)
-   name
-   description (nullable)
-   timestamps

### subjects

-   id (primary)
-   email (unique)
-   first_name
-   last_name
-   timestamps

### project_subject

-   project_id (foreign, cascade)
-   subject_id (foreign, cascade)
-   timestamps
-   "project_id, subject_id" (primary)
