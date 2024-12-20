# EventApp

EventApp is a web application designed to manage events with locations, activities, and comments. The application supports CRUD operations for events, activities, users, locations, and comments. It also includes user authentication with different roles (Admin, User, Banned) and dynamic content based on user roles and login status.

## Features

- CRUD operations for events, activities, users, locations, and comments
- User authentication (login, register, password reset)
- Three different user roles (Admin, User, Banned)
- Dynamic content based on user roles:
    - If **ADMIN**, display a button to access the admin panel
    - If **USER**, display a button to access the user profile
    - If **BANNED**, display a message indicating the user is banned and hide other pages
- Dynamic content based on login status:
    - If logged in, display the user's name and surname
    - If not logged in, display a login button
    - If logged in, display a logout button
- Search and filter system
- Admin-specific features:
    - Display a button to access the list of users

## Getting Started

To get started with EventApp, follow these steps:

1. Clone the repository:

git clone <repository-url>

2. Build and start the Docker containers:

docker compose --build -d

3. Connect to the PHP container:

docker ps
docker exec -it <php-container-id> bash

4. Inside the PHP container, run the following commands:

php bin/console tailwind:build --watch
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

5. When prompted, type `yes` and press Enter to load the fixtures.

The project is now set up. You can explore the site using the following user accounts:

### User account:

- **Email:** user1@example.com
- **Password:** userpass

### Admin account:

- **Email:** admin@example.com
- **Password:** adminpass

### Banned account:

- **Email:** banned@example.com
- **Password:** bannedpass
