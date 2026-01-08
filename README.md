# PHP Authentication System

## Description
This project is a PHP-based authentication system that supports user registration (sign up), user login (sign in), and session-based access control. The system performs input validation during registration and login, and displays the authenticated user's stored information on a protected page after a successful login.

## Features
- User registration (sign up) with form validation
- User login (sign in) with credential validation
- Session-based authentication and access control
- Display of user information after successful login
- Logout functionality
- File-based user storage using JSON

## Project Structure
Login/
├── login.php
├── signup.php
├── logout.php
├── home.php
├── style.css
users.json (ignored in version control)

markdown
Copy code

## Requirements
- PHP (XAMPP or equivalent local server)
- Web browser

## How to Run
1. Place the project inside the `htdocs` directory.
2. Start Apache using XAMPP.
3. Open the application in your browser:
http://localhost/php-authentication-system/Login/login.php

pgsql
Copy code

## Data Storage Note
User data is stored locally in a JSON file. The `users.json` file is excluded from version control to prevent exposure of sensitive information.

## Notes
This project can be extended with database integration and enhanced security features.

## Author
Joy Ann Anawen G. Balaguer
