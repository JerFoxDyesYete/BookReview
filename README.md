# Book Review API with Authentication & Email Notifications

## Overview

This API provides book review functionality with secure authentication. Users can register, login, and manage their book reviews. The system includes email notifications for review activities.

## Features

- User registration and authentication
- JWT token-based security
- CRUD operations for book reviews
- Email notifications via Mailtrap

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/JerFoxDyesYete/BookReview/
   cd BookReview
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Set up environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure Mailtrap in `.env`:
   ```ini
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_mailtrap_username
   MAIL_PASSWORD=your_mailtrap_password
   MAIL_ENCRYPTION=tls
   ```

5. Run migrations:
   ```bash
   php artisan migrate
   ```

6. Start the development server:
   ```bash
   php artisan serve
   ```

## API Endpoints

### Authentication

| Method | Endpoint       | Description                |
|--------|----------------|----------------------------|
| POST   | /api/register  | Register a new user        |
| POST   | /api/login     | Login and get JWT token   |
| POST   | /api/logout    | Logout (invalidate token) |

### Book Reviews

| Method | Endpoint                | Description                    |
|--------|-------------------------|--------------------------------|
| GET    | /api/book-reviews       | Get all reviews                |
| POST   | /api/book-reviews       | Create a new review (protected)|
| GET    | /api/book-reviews/{id}  | Get a specific review          |
| PUT    | /api/book-reviews/{id}  | Update a review (protected)    |
| DELETE | /api/book-reviews/{id}  | Delete a review (protected)    |

## Usage Examples

### Registration

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Create Review (Authenticated)

```bash
curl -X POST http://localhost:8000/api/book-reviews \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "book_title": "Laravel for Beginners",
    "author": "John Doe",
    "review": "Great book for learning Laravel!",
    "rating": 5
  }'
```

## Testing Email Notifications

1. Sign up with a real email address
2. Create/update/delete reviews
3. Check your Mailtrap inbox for notifications

## Security

- JWT authentication
- Password hashing
- CSRF protection
- Rate limiting (60 requests/minute)

## License

MIT License
