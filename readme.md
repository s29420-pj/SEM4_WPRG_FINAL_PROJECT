# On the Trail of Adventure

## Project Description

On the Trail of Adventure is an interactive platform for adventure and travel enthusiasts. It allows users to create and share posts about their travels, as well as browse posts from other users.

## Technologies

The project was implemented using the following technologies:
- PHP: Programming language used for server-side logic.
- JavaScript: Used for interactive elements on the page.
- Composer: Dependency manager for PHP, used for managing libraries and packages.

## Installation

To run the project locally, follow these steps:

1. Clone the project repository:
```bash
git clone https://github.com/s29420-pj/SEM4_WPRG_FINAL_PROJECT.git
```

2. Install dependencies using Composer:
```bash
cd [PROJECT_DIRECTORY_NAME]
composer install
```

3. Configure the `.env` file with the appropriate database access data:
```dotenv
DB_HOST=[DATABASE_HOST_ADDRESS]
DB_USERNAME=[DATABASE_USERNAME]
DB_PASSWORD=[DATABASE_PASSWORD]
DB_NAME=[DATABASE_NAME]
```

4. Start the development server:
```bash
php -S localhost:8000 -t public
```

Visit `http://localhost:8000` in your browser to see the application running.

## Project Structure

- `app/`: Directory containing the application logic (models, views, controllers).
- `public/`: Public server directory, contains `index.php` and static resources (CSS, JS, images).
- `vendor/`: Directory with Composer dependencies.
- `.env`: Configuration file with database access data.

## Features

- Creating and managing posts.
- User authentication and authorization.
- Sending emails through a contact form.

## Author

Index: s29420
```bash
Replace `[REPOSITORY_URL]`, `[PROJECT_DIRECTORY_NAME]`, `[DATABASE_HOST_ADDRESS]`, `[DATABASE_USERNAME]`, `[DATABASE_PASSWORD]`, and `[DATABASE_NAME]` with the appropriate values for your project.
```