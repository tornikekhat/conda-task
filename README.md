# Laravel + Vue.js Contact Form

This repository contains a Laravel backend API and a Vue.js frontend for a simple contact form. The form allows users to submit their name, email, subject, and message.

## Features

- **Vue.js frontend** for form handling and validation
- **Laravel backend** API for form submission
- **Client-side validation** for required fields and valid email format
- **TailwindCSS** for styling

## Requirements

- Docker (for running the project in containers)
- Docker Compose (for managing multiple containers)

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/tornikekhat/conda-task.git
cd conda-task
```

### 2. Set up Docker

To build and start the Docker containers for the Laravel backend, Vue.js frontend, MySQL database, and Mailhog, run the following command:

```bash
docker-compose up -d --build
```

### 3. Configure the .env file

After the containers are up, copy the .env.example to .env in the project root. You can do this inside the container or locally:

```bash
docker-compose exec app cp .env.example .env
```

### 4. Run migrations and generate the application key

Once the containers are set up, you need to run the database migrations and generate the application key. You can do this by running the following commands inside the Laravel container:

```bash
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
```

### 5. Access the application
Usage
- Open the frontend in your browser at http://localhost:8000.
- Fill out the contact form with your details.
- The form will perform client-side validation.
- Upon successful validation, the form data will be sent to the Laravel backend.
- Laravel will also validate the data on the server side.
- If both client-side and server-side validation pass, the backend will process the form and send an email.
- To view the email, open MailHog in your browser at http://localhost:8025.

API Endpoints
- POST /api/contacts

Body parameters:
- name: string, required
- email: string, required, must be a valid email
- subject: string, required
- message: string, required

Response:
- status: HTTP status code
- message: success or error message

Example success response:

```
{
  "status": 201,
  "message": "Contact submitted successfully"
}
```

Example error response:

```
{
  "status": 500,
  "message": "Email failed to send",
  "error": "Unable to connect or send email"
}
```

## License

This project is licensed under the MIT License - see the LICENSE file for details.
