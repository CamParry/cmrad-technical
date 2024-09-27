# CMRAD Technical

-   A simple API to manage clinical trial Projects and Subjects

## Run app

TODO...

## Entities

### Projects

CRUD + Assign to subjects by id

-   id (primary)
-   name
-   description (optional)
-   subject_count (calculated)
-   timestamps

### Subjects

CRUD + Assign to projects by id

-   id (primary)
-   email
-   first_name
-   last_name
-   timestamps

### ProjectSubject

-   project_id
-   subject_id
-   timestamps
-   "project_id, subject_id" (primary)
