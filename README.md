## Forum application built with Laravel

A forum-style web application where users can create threads, posts, and comments, as well as interact with other users. 

This project was built mainly to develop practical skills in Laravel, backend architecture, and database-driven applications.

## Features

- User authentication
- Create, edit, and delete posts
- Thread and reply system
- Authorization using policies
- User following system
- Direct messaging between users
- Notifications system

## Tech stack

- Laravel
- Tailwind CSS

## What i learned through building this

- Understanding of MVC architecture in Laravel
- Implementing authentication and authorization (policies)
- Working with relational databases and Eloquent relationships
- Structuring a full-stack application using Laravel and Tailwind CSS

## Future improvements

- Refactor validation logic into Form Request classes
- Introduce a service layer to better separate business logic
- Improve consistency in code structure across the project
- Add automated tests

## Setup instructions

1. Clone the repository  
2. Run `composer install`  
3. Copy `.env.example` to `.env`  
4. Run `php artisan key:generate`  
5. Run `php artisan migrate`  
6. Start the server with `php artisan serve`
