# Technical Challenge: Subject & Project Management

## Context:

In this challenge, we are going to design a simple API to manage _subjects_ and _projects_ for clinical trials. Subjects participate in clinical trials, and each subject can be assigned to multiple projects.

## Entities:

### 1. Subject

Subjects represent individuals participating in clinical trials, and they have the following attributes:

```json
{
  "email", // required
  "first_name", // required
  "last_name", // required
}
```

### 2. Project

Projects represent clinical trials, and they have the following attributes:

```json
{
  "name", // required
  "description", // optional
}
```

## Requirements

The API must expose endpoints for:

-   Adding, retrieving, updating, and deleting subjects.
-   Adding, retrieving, and updating projects. (When retrieving project information, the API must include the number of subjects enrolled in that project)
-   Assigning subjects to one or more projects.

### Constraints

-   A subject cannot be assigned to the same project more than once.
-   Subjects must be able to enroll in multiple projects.
