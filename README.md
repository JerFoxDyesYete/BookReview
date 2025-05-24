Book Reviews API - Laravel RESTful API with Authentication
Overview
This is a RESTful API for managing book reviews with user authentication. The API allows users to register, login, and perform CRUD operations on book reviews with proper authentication.

Features
User registration and authentication

CRUD operations for book reviews

Token-based authentication using Laravel Sanctum

Data validation

Ready for email notifications (to be implemented)

Prerequisites
PHP 8.0+

Composer

MySQL

Laravel 9.x

Installation
Clone the repository:

bash
git clone [repository-url]
cd book-reviews-api
Install dependencies:

bash
composer install
Create and configure the .env file:

bash
cp .env.example .env
Generate application key:

bash
php artisan key:generate
Configure your database in .env:

ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=book_reviews_api
DB_USERNAME=your_username
DB_PASSWORD=your_password
Run migrations:

bash
php artisan migrate
Start the development server:

bash
php artisan serve
API Endpoints
Authentication
POST /api/register - Register a new user

json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password"
}
POST /api/login - Login an existing user

json
{
  "email": "john@example.com",
  "password": "password"
}
POST /api/logout - Logout the current user (requires authentication)

Book Reviews
GET /api/book-reviews - List all book reviews (public)

GET /api/book-reviews/{id} - Get a specific book review (public)

POST /api/book-reviews - Create a new book review (requires authentication)

json
{
  "book_title": "Laravel Guide",
  "author": "John Doe",
  "review": "Great book!",
  "rating": 5
}
PUT/PATCH /api/book-reviews/{id} - Update a book review (requires authentication)

DELETE /api/book-reviews/{id} - Delete a book review (requires authentication)

Authentication Flow
Register a new user:

Send a POST request to /api/register with user details

You'll receive a response containing the user data and an authentication token

Login:

Send a POST request to /api/login with email and password

You'll receive a response containing the user data and an authentication token

Making authenticated requests:

Include the received token in the Authorization header of subsequent requests:

Authorization: Bearer YOUR_TOKEN_HERE
Logout:

Send a POST request to /api/logout with the authentication token

The token will be revoked and can no longer be used

Example Requests
Register a User
bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password","password_confirmation":"password"}'
Login
bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'
Create a Book Review (Authenticated)
bash
curl -X POST http://localhost:8000/api/book-reviews \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{"book_title":"Laravel Guide","author":"John Doe","review":"Great book!","rating":5}'
Get All Book Reviews
bash
curl -X GET http://localhost:8000/api/book-reviews \
  -H "Content-Type: application/json"
Testing
You can test the API using tools like:

Postman

Insomnia

cURL (as shown in examples)

Laravel's built-in testing (PHPUnit)

Security Considerations
Always use HTTPS in production

Keep your authentication tokens secure

Never commit your .env file to version control

Implement rate limiting if needed

Consider adding email verification for users

Future Enhancements
Email verification

Password reset functionality

Email notifications for:

New reviews

Review updates

Welcome emails

Role-based permissions

API documentation with Swagger/OpenAPI

Troubleshooting
Token not working: Ensure you're including the Bearer prefix in the Authorization header

Authentication errors: Verify the token hasn't expired or been revoked

Validation errors: Check the response body for specific error messages about invalid data

Database issues: Verify your database credentials in .env and that migrations have run successfully

Support
For any issues or questions, please contact [your support email or contact method].