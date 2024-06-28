# Electronic Voting System - Laravel Backend API Documentation

This repository contains the backend API implementation for an electronic voting system using Laravel. It provides endpoints for user authentication, voter registration, candidate management, voting, and admin operations.

## Table of Contents

-   [Installation](#installation)
-   [Usage](#usage)
-   [Configuration](#configuration)
-   [Contributing](#contributing)
-   [License](#license)
-   [Credits](#credits)

## Installation

### Prerequisites

-   PHP 8.1 or higher
-   Composer
-   MySQL

### Steps

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/suchithrashiju/e-voting-backend.git
    cd e-voting-backend
    ```
2.  **Install PHP dependencies:**

    ```bash
    composer install
    ```

3.  **Copy the .env.example file to .env:**
    cp .env.example .env

4.  **Set up your .env file:**
    Update the .env file with your database credentials and other necessary configuration.

    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password

    APP_IMAGE_URL="http://127.0.0.1:8000/"


    ```

5.  **Generate an application key:**
    php artisan key:generate
6.  **Run database migrations:**

```bash
  php artisan migrate

```

8.  **Seed the database:**

```bash
  php artisan db:seed
```

    To seperate seed the database with initial data:

    php artisan db:seed --class=CandidateSeeder
    php artisan db:seed --class=UserSeeder

    User Seeder (UserSeeder.php) - for admin
    Candidate Seeder (CandidateSeeder.php)

    This seeder will create three initial candidates in the database.

9. **Start the development server:**

```bash
php artisan serve
```

**Usage**

**Admin Login Details**

To log in as an admin:

    Email: admin@example.com
    Password: password123

**API Routes**
Public Routes
Admin Authentication

    POST /api/admin-login
        Description: Authenticate an admin.
        Controller: AuthController@login
        Parameters: email, password

Voter Authentication and Registration

    POST /api/voter-register
        Description: Register a new voter.
        Controller: AuthController@voterRegister
        Parameters: name, email, password, dob, address, phone

    POST /api/voter-login
        Description: Authenticate a voter.
        Controller: AuthController@voterLogin
        Parameters: email, password

Candidate Management

    GET /api/candidates
        Description: Retrieve all candidates.
        Controller: CandidateController@index

    POST /api/candidates
        Description: Create a new candidate (Admin only).
        Controller: CandidateController@store
        Parameters: name, party, photo_url, symbol_url, election_year

    GET /api/candidates/{id}
        Description: Retrieve a specific candidate.
        Controller: CandidateController@show
        Parameters: id

    PUT /api/candidates/{id}
        Description: Update candidate details (Admin only).
        Controller: CandidateController@update
        Parameters: id, name, party, photo_url, symbol_url, election_year

    DELETE /api/candidates/{id}
        Description: Delete a candidate (Admin only).
        Controller: CandidateController@destroy
        Parameters: id

Protected Routes (Authenticated via Sanctum)
Authenticated User

    GET /api/user
        Description: Retrieve authenticated user information.
        Controller: Closure function

Voter Operations

    GET /api/voters
        Description: Retrieve all voters (Admin only).
        Controller: VoterController@index

    GET /api/voters/{id}
        Description: Retrieve a specific voter (Admin only).
        Controller: VoterController@show
        Parameters: id

    PUT /api/voters/{id}
        Description: Update voter details (Admin only).
        Controller: VoterController@update
        Parameters: id, name, email, dob, address, phone

    DELETE /api/voters/{id}
        Description: Delete a voter (Admin only).
        Controller: VoterController@destroy
        Parameters: id

Voting

    POST /api/votes
        Description: Record a vote for a candidate.
        Controller: VoteController@store
        Parameters: candidate_id

    GET /api/votes
        Description: Retrieve all votes (Admin only).
        Controller: VoteController@index

    GET /api/votes/status
        Description: Check if the voter has already voted.
        Controller: VoteController@hasVoted

    POST /api/votes/validate-aadhar
        Description: Validate voter's Aadhar number.
        Controller: VoteController@isValidAadhar
        Parameters: aadhar_number

Admin Operations

    GET /api/admin/results
        Description: Retrieve election results.
        Controller: VoteController@results
        Middleware: AdminMiddleware

License

This project is licensed under the MIT License. See the LICENSE file for details.
Credits

    Author: Suchithra
    Contributors: List of contributors

Acknowledgments

    Laravel Framework 11
    MySQL
