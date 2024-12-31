# Daily Weight Logs API

This is a Laravel-based API application for tracking users' weight logs, health data, and managing user accounts. It provides endpoints for users to log their weight twice daily (morning and evening), view their historical data, and for admins to manage user data.

---

## Features
- Weight Logging: Users can log their weight for morning and evening each day.
- Health Data Management: Users can store and update their health data.
- User Profiles: Users can manage their profile information.
- Admin Controls: Admins can manage users, view and delete health data, and weight logs.
- Filtering: Weight logs can be filtered by time of day (morning/evening).
- API Documentation: Comprehensive API documentation generated with Scribe.

## Technologies Used
- Backend Framework: Laravel 11
- API Documentation: Scribe
- Database: MySQL
- Authentication: Sanctum
- Containerization: Docker with Laravel Sail

## Prerequisites

**Ensure you have the following installed on your system:**
- Docker
- Laravel Sail
- Composer
- PHP (if running without Docker)

## Setup Instructions
**Clone the repository:**
```bash
git clone https://github.com/your-repository-name.git
cd your-repository-name
```
**Install dependencies:**
```bash
composer install
```
**Start the application using Laravel Sail:**
```bash
./vendor/bin/sail up
```
**Run database migrations and seeders:**
```bash
./vendor/bin/sail artisan migrate --seed
```
**Generate API documentation using Scribe:**
```bash
./vendor/bin/sail artisan scribe:generate
```
**Access the API documentation**

- The generated documentation is available at: `http://localhost/docs`.

**Usage**

Running Tests
Run the test suite to ensure the application is working as expected:
```bash
./vendor/bin/sail artisan test
```
**API Endpoints**

All endpoints are documented with Scribe. Visit the generated documentation at:
- [API Documentation](http://localhost/docs)

**Examples**

**Creating a Weight Log**

POST `/api/v1/weight-logs`
{
  "weight": 75.5,
  "time_of_day": "morning",
  "logged_at": "2024-12-31T07:30:00"
}

**Filtering Weight Logs by Time of Day**

GET `/api/v1/weight-logs?time_of_day=morning`

**Updating User Profile**

PUT `/api/v1/profile`
{
  "name": "Updated Name",
  "email": "updatedemail@example.com"
}

## Contributing
If you would like to contribute to this project, feel free to submit a pull request or create an issue.

## License
This project is licensed under the MIT License. See the `LICENSE` file for details.

## Author
Kennedy Owusu
GitHub: [kennedyowusu](https://github.com/kennedyowusu)

API Documentation: [dexwin.kennedyowusu.com](https://dexwin.kennedyowusu.com/public/docs/)
