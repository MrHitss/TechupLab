# ToDO App Management API

## Requirements

- PHP >= 8.0
- Composer
- SQLite (or any other database supported by Laravel)
- Laravel Sanctum

## Installation Steps

1. **Clone the repository**

   ```bash
   git clone https://github.com/MrHitss/TechupLab
   cd TechupLab
   ```
   
2. **Install Dependencies**

   ```bash
   composer install
   ```

3. **Create .env file**

   ```bash
   cp .env.example .env
   ```
   
4. **Generate Application Key**

   ```bash
   php artisan key:generate
   ```

5. **Run Migration**

   ```bash
   php artisan migrate
   ```
   
6. **Install Laravel Sanctum**

   ```bash
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   php artisan migrate
   ```

7. **Run the Project**

   ```bash
   php artisan serve
   ```
   
## API Endpoints

1. **Send POST /api/register** 

   ```json
   {
       "name": "John Doe",
       "email": "john@example.com",
       "password": "password"
   }
   ```

2. **Send POST /api/login**

   ```json
   {
       "email": "john@example.com",
       "password": "password"
   }
   ```

3. **Send POST /api/tasks**

   ```json
   {
       "subject": "Task subject",
       "description": "Task description",
       "start_date": "2024-10-15",
       "due_date": "2024-10-20",
       "status": "new",
       "priority": "high",
       "notes": [
            {
              "subject": "Note 1",
              "note": "First note content",
              "attachments": [file1, file2]
            },
            {
              "subject": "Note 2",
              "note": "Second note content",
              "attachments": [file1, file2]
            }
       ],
   }
   ```

4. **Get GET /api/tasks**

   ```json
   {
    "subject": "Task 1",
    "description": "Description of Task 1",
    "start_date": "2024-10-10",
    "due_date": "2024-10-15",
    "status": "New",
    "priority": "High",
    "updated_at": "2024-10-15T07:32:53.000000Z",
    "created_at": "2024-10-15T07:32:53.000000Z",
    "id": 1,
    "notes": [
        {
            "id": 1,
            "task_id": 1,
            "subject": "Note 1",
            "attachments": "[]",
            "note": "This is the first note",
            "created_at": "2024-10-15T07:32:53.000000Z",
            "updated_at": "2024-10-15T07:32:53.000000Z"
        },
        {
            "id": 2,
            "task_id": 1,
            "subject": "Note 2",
            "attachments": "[]",
            "note": "This is the second note",
            "created_at": "2024-10-15T07:32:53.000000Z",
            "updated_at": "2024-10-15T07:32:53.000000Z"
        }
    ]
   }
   ```
   
